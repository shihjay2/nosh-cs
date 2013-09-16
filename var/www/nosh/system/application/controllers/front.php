<?php
class Front extends Controller {

	function Front()
	{
		parent::Controller();
	}

	// --------------------------------------------------------------------

	function index()
	{
		include(APPPATH.'config/database'.EXT);
		if ($db['default']['username'] === "") {
			redirect('install');
		}
		$link = mysql_connect('localhost', $db['default']['username'], $db['default']['password']);
		if (!$link) {
			redirect('install/fix_db_conn');
		}
		$db_selected = mysql_select_db('nosh', $link);
		if (!$db_selected) {
			redirect('install');
		} else {
			$this->check_version();
		}
		
	}
	
	// --------------------------------------------------------------------
	
	function check_version()
	{
		$this->load->database();
		// Check for version 1.1
		if (!$this->db->field_exists('smtp_user','practiceinfo')) {
			$this->system_update1_1();
		}
		// Check for version 1.4.2
		if (!$this->db->field_exists('addendum_eid','encounters')) {
			$this->system_update1_4_2();
		}
		// Check for version 1.5.0
		if (!$this->db->field_exists('rcopia_sync','allergies')) {
			$this->system_update1_5_0();
		}
		// Check for version 1.5.3
		if (!$this->db->field_exists('version','practiceinfo')) {
			$this->system_update1_5_3();
		}
		// Check for version 1.5.5
		$row1 = $this->db->get('practiceinfo')->row_array();
		if ($row1['version'] < "1.5.5") {
			$this->system_update1_5_5();
		}
		// Check for version 1.5.6
		if ($row1['version'] < "1.5.6") {
			$this->system_update1_5_6();
		}
		// Check for version 1.6.0
		if ($row1['version'] < "1.6.0") {
			$this->system_update1_6_0();
		}
		// Check for version 1.6.2
		if ($row1['version'] < "1.6.2") {
			$this->system_update1_6_2();
		}
		// Check for version 1.6.3
		if ($row1['version'] < "1.6.3") {
			$this->system_update1_6_3();
		}
		// Check for version 1.6.4
		if ($row1['version'] < "1.6.4") {
			$this->system_update1_6_4();
		}
		// Check for version 1.6.5
		if ($row1['version'] < "1.6.5") {
			$this->system_update1_6_5();
		}
		// Check for version 1.6.6
		if ($row1['version'] < "1.6.6") {
			$this->system_update1_6_6();
		}
		// Check for version 1.6.7
		if ($row1['version'] < "1.6.7") {
			$this->system_update1_6_7();
		}
		// Check for version 1.6.8
		if ($row1['version'] < "1.6.8") {
			$this->system_update1_6_8();
		}
		// Check for version 1.6.9
		if ($row1['version'] < "1.6.9") {
			$this->system_update1_6_9();
		}
		// Check for version 1.7.0
		if ($row1['version'] < "1.7.0") {
			$this->system_update1_7_0();
		}
		// Check for version 1.7.1
		if ($row1['version'] < "1.7.1") {
			$this->system_update1_7_1();
		}
		//$this->run();
		redirect('start');
	}
	
	function system_update1_1() 
	{
		$this->load->dbforge();
		$this->load->database();
		if (!$this->db->field_exists('smtp_user','practiceinfo')) {
			$practiceinfo_definition = array(
				'smtp_user' => array('type' => 'VARCHAR', 'constraint' => 100),
				'smtp_pass' => array('type' => 'VARCHAR', 'constraint' => 100),
				'patient_portal' => array('type' => 'VARCHAR', 'constraint' => 255)
			);
			$this->dbforge->add_column('practiceinfo', $practiceinfo_definition);
		}
		if (!$this->db->field_exists('active','calendar')) {
			$calendar_definition = array(
				'active' => array('type' => 'VARCHAR', 'constraint' => 4)
			);
			$this->dbforge->add_column('calendar', $calendar_definition);
			$calendar_data = array(
				'active' => 'y'
			);
			$this->db->update('calendar', $calendar_data);
		}
		$this->db->empty_table('templates');
		$template_sql_file = "/var/www/nosh/import/templates.sql";
		include(APPPATH.'config/database'.EXT);
		$template_command = "mysql -u " . $db['default']['username']. " -p". $db['default']['password'] . " nosh < " . $template_sql_file;
		system($template_command);
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
		$this->db->where('practice_id', '1');
		$practice_info_query = $this->db->get('practiceinfo');
		$practice_info_row = $practice_info_query->row_array();
		if ($practice_info_row['fax_type'] == 'nosh') {
			$practice_info_data = array(
				'fax_type' => ''
			);
			$this->db->where('practice_id', '1');
			$this->db->update('practiceinfo', $practice_info_data);
		}
	}
	
	function system_update1_4_2() 
	{
		$this->load->dbforge();
		$this->load->database();
		$encounters_definition = array(
			'addendum' => array('type' => 'VARCHAR', 'constraint' => 4),
			'addendum_eid' => array('type' => 'BIGINT', 'constraint' => 20)
		);
		$this->dbforge->add_column('encounters', $encounters_definition);
		$query = $this->db->get('encounters');
		if ($query->num_rows() > 0) {
			foreach ($query->result_array() as $row) {
				$encounters_data = array(
					'addendum' => 'n',
					'addendum_eid' => $row['eid']
				);
				$this->db->where('eid', $row['eid']);
				$this->db->update('encounters', $encounters_data);
			}
		}
		$demographics_definition = array(
			'rcopia_sync' => array('type' => 'VARCHAR', 'constraint' => 4)
		);
		$this->dbforge->add_column('demographics', $demographics_definition);
		$demographics_data = array(
			'rcopia_sync' => 'n'
		);
		$this->db->update('demographics', $demographics_data);
		$practiceinfo_definition = array(
			'rcopia_extension' => array('type' => 'VARCHAR', 'constraint' => 4),
			'rcopia_apiVendor' => array('type' => 'VARCHAR', 'constraint' => 100),
			'rcopia_apiPass' => array('type' => 'VARCHAR', 'constraint' => 100),
			'rcopia_apiPractice' => array('type' => 'VARCHAR', 'constraint' => 100),
			'rcopia_apiSystem' => array('type' => 'VARCHAR', 'constraint' => 100)
		);
		$this->dbforge->add_column('practiceinfo', $practiceinfo_definition);
		$practiceinfo_data = array(
			'rcopia_extension' => 'n'
		);
		$this->db->update('practiceinfo', $practiceinfo_data);
		$meds_definition = array(
			'meds_ndcid' => array('type' => 'VARCHAR', 'constraint' => 11),
			'meds_package' => array('type' => 'VARCHAR', 'constraint' => 255)
		);
		$this->dbforge->add_column('meds', $meds_definition);
		$this->db->empty_table('icd9');
		$icd_file = "/var/www/nosh/import/icd9.sql";
		include(APPPATH.'config/database'.EXT);
		$icd_command = "mysql -u " . $db['default']['username']. " -p". $db['default']['password'] . " nosh < " . $icd_file;
		system($icd_command);
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
	}
	
	function system_update1_5_0() 
	{
		$this->load->dbforge();
		$this->load->database();
		$allergies_definition = array(
			'rcopia_sync' => array('type' => 'VARCHAR', 'constraint' => 4),
			'meds_ndcid' => array('type' => 'VARCHAR', 'constraint' => 11)
		);
		$this->dbforge->add_column('allergies', $allergies_definition);
		$allergies_data = array(
			'rcopia_sync' => 'n'
		);
		$this->db->update('allergies', $allergies_data);
		$rx_list_definition = array(
			'rcopia_sync' => array('type' => 'VARCHAR', 'constraint' => 4),
			'rxl_ndcid' => array('type' => 'VARCHAR', 'constraint' => 11)
		);
		$this->dbforge->add_column('rx_list', $rx_list_definition);
		$rx_list_data = array(
			'rcopia_sync' => 'n'
		);
		$this->db->update('rx_list', $rx_list_data);
		$issues_definition = array(
			'rcopia_sync' => array('type' => 'VARCHAR', 'constraint' => 4)
		);
		$this->dbforge->add_column('issues', $issues_definition);
		$issues_data = array(
			'rcopia_sync' => 'n'
		);
		$this->db->update('issues', $issues_data);
		$active_rx_query = $this->db->query("SELECT * FROM rx_list WHERE rxl_date_inactive='0000-00-00 00:00:00' AND rxl_date_old='0000-00-00 00:00:00'");
		if ($active_rx_query->num_rows() > 0) {
			foreach ($active_rx_query->result_array() as $active_rx_row) {
				$rx_pos = strpos($active_rx_row['rxl_medication'], ",");
				$name = substr($active_rx_row['rxl_medication'], 0, $rx_pos);
				$rx_pos1 = $rx_pos + 2;
				$form = substr($active_rx_row['rxl_medication'], $rx_pos1);
				$this->db->where('PROPRIETARYNAME', $name);
				$this->db->where('DOSAGEFORMNAME', $form);
				$this->db->where('ACTIVE_NUMERATOR_STRENGTH', $active_rx_row['rxl_dosage']);
				$this->db->where('ACTIVE_INGRED_UNIT', $active_rx_row['rxl_dosage_unit']);
				$meds_query = $this->db->get('meds_full');
				if ($meds_query->num_rows() > 0) {
					$meds_row = $meds_query->row_array();
					$this->db->where('PRODUCTNDC', $meds_row['PRODUCTNDC']);
					$this->db->limit(1);
					$package_result = $this->db->get('meds_full_package')->row_array();
					$pos1 = strpos($package_result['NDCPACKAGECODE'], '-');
					$parts = explode("-", $package_result['NDCPACKAGECODE']);
					if ($pos1 === 4) {
						$parts[0] = '0' . $parts[0];
					} else {
						$pos2 = strrpos($package_result['NDCPACKAGECODE'], '-');
						if ($pos2 === 10) {
							$parts[2] = '0' . $parts[2];
						} else {
							$parts[1] = '0' . $parts[1];
						}
					}
					$ndcid = $parts[0] . $parts[1] . $parts[2];
				} else {
					$ndcid = '';
				}
				$active_rx_data = array (
					'rxl_ndcid' => $ndcid
				);
				$this->db->where('rxl_id', $active_rx_row['rxl_id']);
				$this->db->update('rx_list', $active_rx_data);
			}
		}
		$this->dbforge->drop_table('meds');
		$extensions_definition = array(
			'extensions_id' => array('type' => 'INT', 'constraint' => 40, 'auto_increment' => TRUE),
			'extensions_name' => array('type' => 'VARCHAR', 'constraint' => 255),
			'pid' => array('type' => 'BIGINT', 'constraint' => 20),
			'action' => array('type' => 'VARCHAR', 'constraint' => 255),
			'description' => array('type' => 'LONGTEXT'),
			'timestamp' => array('type' => 'TIMESTAMP')
		);
		$this->dbforge->add_field($extensions_definition);
		$this->dbforge->add_key('extensions_id', TRUE);
		$this->dbforge->create_table('extensions_log', TRUE);
		$demographics_definition = array(
			'rcopia_update_medications' => array('type' => 'VARCHAR', 'constraint' => 4),
			'rcopia_update_medications_date' => array('type' => 'VARCHAR', 'constraint' => 20),
			'rcopia_update_allergy' => array('type' => 'VARCHAR', 'constraint' => 4),
			'rcopia_update_allergy_date' => array('type' => 'VARCHAR', 'constraint' => 20),
			'rcopia_update_prescription' => array('type' => 'VARCHAR', 'constraint' => 4),
			'rcopia_update_prescription_date' => array('type' => 'VARCHAR', 'constraint' => 20)
		);
		$this->dbforge->add_column('demographics', $demographics_definition);
		$providers_definition = array(
			'rcopia_username' => array('type' => 'VARCHAR', 'constraint' => 100)
		);
		$this->dbforge->add_column('providers', $providers_definition);
		$practiceinfo_definition = array(
			'rcopia_update_notification_lastupdate' => array('type' => 'VARCHAR', 'constraint' => 100),
			'updox_extension' => array('type' => 'VARCHAR', 'constraint' => 4)
		);
		$this->dbforge->add_column('practiceinfo', $practiceinfo_definition);
		$date = now();
		$datestring = "%m/%d/%Y %H:%i:%s";
		$date1 = mdate($datestring, $date);
		$practiceinfo_data = array(
			'rcopia_update_notification_lastupdate' => $date1
		);
		$this->db->update('practiceinfo', $practiceinfo_data);
		$this->db->empty_table('templates');
		$template_sql_file = "/var/www/nosh/import/templates.sql";
		include(APPPATH.'config/database'.EXT);
		$template_command = "mysql -u " . $db['default']['username']. " -p". $db['default']['password'] . " nosh < " . $template_sql_file;
		system($template_command);
	}
	
	function system_update1_5_3()
	{
		$this->load->dbforge();
		$this->load->database();
		if (!$this->db->field_exists('version','practiceinfo')) {
			$practiceinfo_definition = array(
				'version' => array('type' => 'VARCHAR', 'constraint' => 20)
			);
			$this->dbforge->add_column('practiceinfo', $practiceinfo_definition);
		}
		$meds_sql_file = "/var/www/nosh/import/meds_full.sql";
		include(APPPATH.'config/database'.EXT);
		$meds_command = "mysql -u " . $db['default']['username']. " -p". $db['default']['password'] . " nosh < " . $meds_sql_file;
		system($meds_command);
		$meds1_sql_file = "/var/www/nosh/import/meds_full_package.sql";
		$meds1_command = "mysql -u " . $db['default']['username']. " -p". $db['default']['password'] . " nosh < " . $meds1_sql_file;
		system($meds1_command);
		$version_data = array(
			'version' => '1.5.3'
		);
		$this->db->update('practiceinfo', $version_data);
	}
	
	function system_update1_5_5()
	{
		$this->load->dbforge();
		$this->load->database();
		$this->db->empty_table('meds_full');
		$this->db->empty_table('meds_full_package');
		$meds_sql_file = "/var/www/nosh/import/meds_full.sql";
		include(APPPATH.'config/database'.EXT);
		$meds_command = "mysql -u " . $db['default']['username']. " -p". $db['default']['password'] . " nosh < " . $meds_sql_file;
		system($meds_command);
		$meds1_sql_file = "/var/www/nosh/import/meds_full_package.sql";
		$meds1_command = "mysql -u " . $db['default']['username']. " -p". $db['default']['password'] . " nosh < " . $meds1_sql_file;
		system($meds1_command);
		$version_data = array(
			'version' => '1.5.5'
		);
		$this->db->update('practiceinfo', $version_data);
	}
	
	function system_update1_5_6()
	{
		$this->load->dbforge();
		$this->load->database();
		$version_data = array(
			'version' => '1.5.6'
		);
		$this->db->update('practiceinfo', $version_data);
	}
	
	function system_update1_6_0()
	{
		$this->load->dbforge();
		$this->load->database();
		if (!$this->db->field_exists('secret_question','users')) {
			$users_definition = array(
				'secret_question' => array('type' => 'VARCHAR', 'constraint' => 255),
				'secret_answer' => array('type' => 'VARCHAR', 'constraint' => 255)
			);
			$this->dbforge->add_column('users', $users_definition);
			$demographics_definition = array(
				'registration_code' => array('type' => 'VARCHAR', 'constraint' => 255)
			);
			$this->dbforge->add_column('demographics', $demographics_definition);
		}
		$version_data = array(
			'version' => '1.6.0'
		);
		$this->db->update('practiceinfo', $version_data);
	}
	
	function system_update1_6_2()
	{
		$this->load->dbforge();
		$this->load->database();
		$version_data = array(
			'version' => '1.6.2'
		);
		$this->db->update('practiceinfo', $version_data);
	}
	
	function system_update1_6_3()
	{
		$this->load->dbforge();
		$this->load->database();
		if (!$this->db->field_exists('mtm_extension','practiceinfo')) {
			$practiceinfo_definition = array(
				'mtm_extension' => array('type' => 'VARCHAR', 'constraint' => 4),
				'practice_logo' => array('type' => 'VARCHAR', 'constraint' => 255),
				'mtm_logo' => array('type' => 'VARCHAR', 'constraint' => 255)
			);
			$this->dbforge->add_column('practiceinfo', $practiceinfo_definition);
		}
		if (!$this->db->field_exists('mtm_id','mtm')) {
			$mtm_definition = array(
				'mtm_id' => array('type' => 'INT', 'constraint' => 40, 'auto_increment' => TRUE),
				'pid' => array('type' => 'BIGINT', 'constraint' => 20),
				'mtm_description' => array('type' => 'LONGTEXT'),
				'mtm_recommendations' => array('type' => 'LONGTEXT'),
				'mtm_beneficiary_notes' => array('type' => 'LONGTEXT'),
				'complete' => array('type' => 'VARCHAR', 'constraint' => 4)
			);
			$this->dbforge->add_field($mtm_definition);
			$this->dbforge->add_key('mtm_id', TRUE);
			$this->dbforge->create_table('mtm', TRUE);
		}
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
		$version_data = array(
			'version' => '1.6.3'
		);
		$this->db->update('practiceinfo', $version_data);
	}
	
	function system_update1_6_4()
	{
		$this->load->dbforge();
		$this->load->database();
		if (!$this->db->field_exists('mtm_action','mtm')) {
			$mtm_definition = array(
				'mtm_action' => array('type' => 'LONGTEXT'),
				'mtm_outcome' => array('type' => 'LONGTEXT'),
				'mtm_related_conditions' => array('type' => 'LONGTEXT'),
				'mtm_duration' => array('type' => 'VARCHAR', 'constraint' => 255),
				'mtm_date_completed' => array('type' => 'DATE')
			);
			$this->dbforge->add_column('mtm', $mtm_definition);
		}
		if (!$this->db->field_exists('mtm_alert_users','practiceinfo')) {
			$practiceinfo_definition = array(
				'mtm_alert_users' => array('type' => 'LONGTEXT')
			);
			$this->dbforge->add_column('practiceinfo', $practiceinfo_definition);
		}
		$version_data = array(
			'version' => '1.6.4'
		);
		$this->db->update('practiceinfo', $version_data);
	}
	
	function system_update1_6_5()
	{
		$this->load->dbforge();
		$this->load->database();
		$cpt_array[] = array(
			'cpt' => '99605',
			'cpt_description' => "Medication therapy management service(s) provided by a pharmacist, individual, face-to-face with patient, with assessment and intervention if provided; initial 15 minutes, new patient\r"
		);
		$cpt_array[] = array(
			'cpt' => '99606',
			'cpt_description' => "Medication therapy management service(s) provided by a pharmacist, individual, face-to-face with patient, with assessment and intervention if provided; initial 15 minutes, established patient\r"
		);
		$cpt_array[] = array(
			'cpt' => '99607',
			'cpt_description' => "Medication therapy management service(s) provided by a pharmacist, individual, face-to-face with patient, with assessment and intervention if provided; each additional 15 minutes\r"
		);
		$cpt_array[] = array(
			'cpt' => '99355',
			'cpt_description' => "Prolonged physician service in the office or other outpatient setting requiring direct (face-to-face) patient contact beyond the usual service; each additional 30 minutes (list separately in addition to code for prolonged physician service)\r"
		);
		foreach ($cpt_array as $cpt_data) {
			$this->db->insert('cpt', $cpt_data);
		}
		$version_data = array(
			'version' => '1.6.5'
		);
		$this->db->update('practiceinfo', $version_data);
	}
	
	function system_update1_6_6()
	{
		$version_data = array(
			'version' => '1.6.6'
		);
		$this->db->update('practiceinfo', $version_data);
	}
	
	function system_update1_6_7()
	{
		$this->load->dbforge();
		$this->load->database();
		$this->db->truncate('icd9');
		$icd_file = "/var/www/nosh/import/icd9.sql";
		include(APPPATH.'config/database'.EXT);
		$icd_command = "mysql -u " . $db['default']['username']. " -p". $db['default']['password'] . " nosh < " . $icd_file;
		system($icd_command);
		$supplement_inventory_definition = array(
			'supplement_id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE),
			'date_purchase' => array('type' => 'DATETIME'),
			'sup_description' => array('type' => 'LONGTEXT'),
			'sup_strength' => array('type' => 'VARCHAR', 'constraint' => 255),
			'sup_manufacturer' => array('type' => 'VARCHAR', 'constraint' => 255),
			'sup_expiration' => array('type' => 'DATETIME'),
			'cpt' => array('type' => 'VARCHAR', 'constraint' => 255),
			'charge' => array('type' => 'VARCHAR', 'constraint' => 255),
			'quantity' => array('type' => 'INT', 'constraint' => 11)
		);
		$this->dbforge->add_field($supplement_inventory_definition);
		$this->dbforge->add_key('supplement_id', TRUE);
		$this->dbforge->create_table('supplement_inventory', TRUE);
		if (!$this->db->field_exists('supplement_id','sup_list')) {
			$supplement_definition = array(
				'supplement_id' => array('type' => 'INT', 'constraint' => 11)
			);
			$this->dbforge->add_column('sup_list', $supplement_definition);
		}
		$version_data = array(
			'version' => '1.6.7'
		);
		$this->db->update('practiceinfo', $version_data);
	}
	
	function system_update1_6_8()
	{
		$this->load->dbforge();
		$this->load->database();
		if (!$this->db->field_exists('additional_message','practiceinfo')) {
			$practiceinfo_definition = array(
				'additional_message' => array('type' => 'LONGTEXT')
			);
			$this->dbforge->add_column('practiceinfo', $practiceinfo_definition);
		}
		if (!$this->db->field_exists('race_code','demographics')) {
			$demographics_definition = array(
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
				'lang_code' => array('type' => 'VARCHAR', 'constraint' => 100)
			);
			$this->dbforge->add_column('demographics', $demographics_definition);
			$this->db->where('race !=', '');
			$query1 = $this->db->get('demographics');
			if ($query1->num_rows() > 0) {
				foreach ($query1->result_array() as $row1) {
					if ($row1['race'] == 'White') {
						$data1['race_code'] = "2106-3";
					}
					if ($row1['race'] == 'American Indian or Alaska Native') {
						$data1['race_code'] = "1002-5";
					}
					if ($row1['race'] == 'Asian') {
						$data1['race_code'] = "2028-9";
					}
					if ($row1['race'] == 'Black or African American') {
						$data1['race_code'] = "2054-5";
					}
					if ($row1['race'] == 'Native Hawaiian or Other Pacific Islander') {
						$data1['race_code'] = "2076-8";
					}
					$this->db->where('pid', $row1['pid']);
					$this->db->update('demographics', $data1);
				}
			}
			$this->db->where('ethnicity !=', '');
			$query2 = $this->db->get('demographics');
			if ($query2->num_rows() > 0) {
				foreach ($query2->result_array() as $row2) {
					if ($row2['ethnicity'] == 'Hispanic or Latino') {
						$data2['ethnicity_code'] = "2135-2";
					}
					if ($row2['ethnicity'] == 'Not Hispanic or Latino') {
						$data2['ethnicity_code'] = "2186-5";
					}
					$this->db->where('pid', $row2['pid']);
					$this->db->update('demographics', $data2);
				}
			}
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
		if (!$this->db->field_exists('rx_orders_summary','rx')) {
			$rx_definition = array(
				'rx_orders_summary' => array('type' => 'LONGTEXT'),
				'rx_supplements_orders_summary' => array('type' => 'LONGTEXT')
			);
			$this->dbforge->add_column('rx', $rx_definition);
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
		$string = '{"html":[{"type":"div","class":"pe_buttonset","id":"pe_gi1_1_div","html":[{"type":"span","html":"Tenderness"},{"type":"br"},{"type":"checkbox","id":"pe_gi1_1a","class":"pe_normal","value":"No abdominal tenderness.","name":"pe_gi1_1","caption":"Normal"},{"type":"checkbox","id":"pe_gi1_1b","class":"pe_other","value":"RIGHT UPPER QUADRANT TENDERNESS.","name":"pe_gi1_1","caption":"RUQ"},{"type":"checkbox","id":"pe_gi1_1c","class":"pe_other","value":"LEFT UPPER QUADRANT TENDERNESS.","name":"pe_gi1_1","caption":"LUQ"},{"type":"checkbox","id":"pe_gi1_1d","class":"pe_other","value":"RIGHT LOWER QUADRANT TENDERNESS.","name":"pe_gi1_1","caption":"RLQ"},{"type":"checkbox","id":"pe_gi1_1e","class":"pe_other","value":"LEFT LOWER QUADRANT TENDERNESS.","name":"pe_gi1_1","caption":"LLQ"},{"type":"checkbox","id":"pe_gi1_1f","class":"pe_other","value":"EPIGASTRIC TENDERNESS.","name":"pe_gi1_1","caption":"Epigastric"},{"type":"br"},{"type":"checkbox","id":"pe_gi1_1g","class":"pe_other","value":"DIFFUSE ABDOMINAL TENDERNESS.","name":"pe_gi1_1","caption":"Diffuse"},{"type":"checkbox","id":"pe_gi1_1h","class":"pe_other","value":"REBOUND TENDERNESS.","name":"pe_gi1_1","caption":"Rebound"},{"type":"checkbox","id":"pe_gi1_1i","class":"pe_other","value":"INVOLUNTARY GUARDING WITH PALPATION.","name":"pe_gi1_1","caption":"Involuntary guarding"}]},{"type":"br"},{"type":"div","class":"pe_buttonset","id":"pe_gi1_2_div","html":[{"type":"span","html":"Tympanic"},{"type":"br"},{"type":"checkbox","id":"pe_gi1_2a","class":"pe_normal","value":"Abdomen not tympanitic.","name":"pe_gi1_2","caption":"No"},{"type":"checkbox","id":"pe_gi1_2b","class":"pe_other","value":"ABDOMEN TYMPANITIC.","name":"pe_gi1_2","caption":"Yes"}]}]}';
		$template_data['array'] = serialize(json_decode($string));
		$this->db->where('group', 'pe_gi1');
		$this->db->update('templates', $template_data);
		$string1 = '{"html":[{"type":"div","class":"pe_buttonset","id":"pe_gu9_1_div","html":[{"type":"span","html":"Enlarged"},{"type":"br"},{"type":"checkbox","id":"pe_gu9_1a","class":"pe_normal","value":"Normal sized prostate.","name":"pe_gu9_1","caption":"No"},{"type":"checkbox","id":"pe_gu9_1b","class":"pe_other","value":"PROSTAE ENLARGED,","name":"pe_gu9_1","caption":"Yes"}]},{"type":"br"},{"type":"div","class":"pe_buttonset","id":"pe_gu9_2_div","html":[{"type":"span","html":"Nodules"},{"type":"br"},{"type":"checkbox","id":"pe_gu9_2a","class":"pe_normal","value":"No prostate nodules noted bilaterally.","name":"pe_gu9_2","caption":"No"},{"type":"checkbox","id":"pe_gu9_2b","class":"pe_other pe_detail","value":"PROSTATE NODULE NOTED ON THE RIGHT SIDE. SIZE: ","name":"pe_gu9_2","caption":"Right"},{"type":"checkbox","id":"pe_gu9_2c","class":"pe_other pe_detail","value":"PROSTATE NODULE NOTED ON THE LEFT SIDE. SIZE: ","name":"pe_gu9_2","caption":"Left"},{"type":"checkbox","id":"pe_gu9_2d","class":"pe_other pe_detail","value":"PROSTATE NODULES NOTED BILATERALLY. SIZE: ","name":"pe_gu9_2","caption":"Bilateral"},{"type":"br"},{"type":"text","id":"pe_gu9_2b_detail","css":{"width":"250px"},"class":"pe_other pe_detail_text","name":"pe_gu9_2","placeholder":"Right Prostate Nodule Details"},{"type":"br"},{"type":"text","id":"pe_gu9_2c_detail","css":{"width":"250px"},"class":"pe_other pe_detail_text","name":"pe_gu9_2","placeholder":"Left Prostate Nodule Details"},{"type":"br"},{"type":"text","id":"pe_gu9_2d_detail","css":{"width":"250px"},"class":"pe_other pe_detail_text","name":"pe_gu9_2","placeholder":"Bilateral Prostate Nodule Details"}]},{"type":"br"},{"type":"div","class":"pe_buttonset","id":"pe_gu9_3_div","html":[{"type":"span","html":"Tender"},{"type":"br"},{"type":"checkbox","id":"pe_gu9_3a","class":"pe_normal","value":"No prostate tenderness noted bilaterally.","name":"pe_gu9_3","caption":"No"},{"type":"checkbox","id":"pe_gu9_3b","class":"pe_other","value":"PROSTATE TENDERNESS NOTED ON THE RIGHT SIDE.","name":"pe_gu9_3","caption":"Right"},{"type":"checkbox","id":"pe_gu9_3c","class":"pe_other","value":"PROSTATE TENDERNESS NOTED ON THE LEFT SIDE.","name":"pe_gu9_3","caption":"Left"},{"type":"checkbox","id":"pe_gu9_3d","class":"pe_other","value":"PROSTATE TENDERNESS NOTED BILATERALLY.","name":"pe_gu9_3","caption":"Bilateral"}]}]}';
		$template_data1['array'] = serialize(json_decode($string1));
		$this->db->where('group', 'pe_gu9');
		$this->db->update('templates', $template_data1);
		if (!$this->db->field_exists('user_id','encounters')) {
			$encounters_definition = array(
				'user_id' => array('type' => 'INT', 'constraint' => 11)
			);
			$this->dbforge->add_column('encounters', $encounters_definition);
		}
		$encounters_query = $this->db->get('encounters');
		if ($encounters_query->num_rows() > 0) {
			foreach ($encounters_query->result_array() as $encounters_row) {
				$this->db->where('displayname', $encounters_row['encounter_provider']);
				$users_query = $this->db->get('users');
				if ($users_query->num_rows() > 0) {
					$users_row = $users_query->row_array();
					$encounters_definition1['user_id'] = $users_row['id'];
					$this->db->where('eid', $encounters_row['eid']);
					$this->db->update('encounters', $encounters_definition1);
				}
			}
		}
		if (!$this->db->field_exists('hippa_role','hippa')) {
			$hippa_definition = array(
				'hippa_role' => array('type' => 'VARCHAR', 'constraint' => 100)
			);
			$this->dbforge->add_column('hippa', $hippa_definition);
		}
		if (!$this->db->field_exists('allergies_severity','allergies')) {
			$allergies_definition = array(
				'allergies_severity' => array('type' => 'VARCHAR', 'constraint' => 255)
			);
			$this->dbforge->add_column('allergies', $allergies_definition);
		}
		if (!$this->db->field_exists('start','repeat_schedule')) {
			$repeat_definition = array(
				'start' => array('type' => 'INT', 'constraint' => 100)
			);
			$this->dbforge->add_column('repeat_schedule', $repeat_definition);
		}
		$repeat_query = $this->db->get('repeat_schedule');
		if ($repeat_query->num_rows() > 0) {
			foreach ($repeat_query->result_array() as $repeat_row) {
				$repeat_data['start'] = "0";
				$this->db->update('repeat_schedule', $repeat_data);
			}
		}
		$sup = $this->db->get('supplement_inventory');
		if ($sup->num_rows() > 0) {
			foreach ($sup->result_array() as $row) {
				$this->db->where('cpt', $row['cpt']);
				$cpt_query = $this->db->get('cpt');
				if ($cpt_query->num_rows() > 0) {
					$cpt_row = $cpt_query->row_array();
					$cpt_data['cpt_charge'] = $row['charge'];
					$this->db->where('cpt_id', $cpt_row['cpt_id']);
					$this->db->update('cpt', $cpt_data);
				}
			}
		}
		$version_data = array(
			'version' => '1.6.8'
		);
		$this->db->update('practiceinfo', $version_data);
	}
	
	function system_update1_6_9()
	{
		$this->load->dbforge();
		$this->load->database();
		$encounters_query = $this->db->get('encounters');
		if ($encounters_query->num_rows() > 0) {
			foreach ($encounters_query->result_array() as $encounters_row) {
				$this->db->where('displayname', $encounters_row['encounter_provider']);
				$users_query = $this->db->get('users');
				if ($users_query->num_rows() > 0) {
					$users_row = $users_query->row_array();
					$encounters_definition1['user_id'] = $users_row['id'];
					$this->db->where('eid', $encounters_row['eid']);
					$this->db->update('encounters', $encounters_definition1);
				}
			}
		}
		$version_data = array(
			'version' => '1.6.9'
		);
		$this->db->update('practiceinfo', $version_data);
	}
	
	function system_update1_7_0()
	{
		$this->load->dbforge();
		$this->load->database();
		if (!$this->db->field_exists('snomed_extension','practiceinfo')) {
			$practiceinfo_definition = array(
				'snomed_extension' => array('type' => 'VARCHAR', 'constraint' => 4),
				'vivacare' => array('type' => 'VARCHAR', 'constraint' => 255),
				'sales_tax' => array('type' => 'VARCHAR', 'constraint' => 10, 'default' => '')
			);
			$this->dbforge->add_column('practiceinfo', $practiceinfo_definition);
		}
		if (!$this->db->field_exists('sup_lot','supplement_inventory')) {
			$supplement_inventory_definition = array(
				'sup_lot' => array('type' => 'VARCHAR', 'constraint' => 255)
			);
			$this->dbforge->add_column('supplement_inventory', $supplement_inventory_definition);
		}
		if (!$this->db->field_exists('snomed','orderslist')) {
			$orderslist_definition = array(
				'snomed' => array('type' => 'VARCHAR', 'constraint' => 255)
			);
			$this->dbforge->add_column('orderslist', $orderslist_definition);
			$orderslist_data = array(
				'user_id' => '0'
			);
			$this->db->update('orderslist', $orderslist_data);
			$snomed1['snomed'] = '167209002';
			$this->db->where('orders_description','Comprehensive metabolic panel (CMP)');
			$this->db->update('orderslist', $snomed1);
			$snomed2['snomed'] = '117356000';
			$this->db->where('orders_description','Complete blood count with platelets and differential (CBC)');
			$this->db->update('orderslist', $snomed2);
			$snomed3['snomed'] = '394977005';
			$this->db->where('orders_description','Antinuclear antibody panel (ANA)');
			$this->db->update('orderslist', $snomed3);
			$snomed4['snomed'] = '270927009';
			$this->db->where('orders_description','Fasting lipid panel');
			$this->db->update('orderslist', $snomed4);
			$snomed5['snomed'] = '104155006';
			$this->db->where('orders_description','Erythrocyte sedimentation rate (ESR)');
			$this->db->update('orderslist', $snomed5);
			$snomed6['snomed'] = '166902009';
			$this->db->where('orders_description','Hemoglobin A1c (HgbA1c)');
			$this->db->update('orderslist', $snomed6);
			$snomed7['snomed'] = '440685005';
			$this->db->where('orders_description','INR');
			$this->db->update('orderslist', $snomed7);
			$snomed8['snomed'] = '143927001';
			$this->db->where('orders_description','Liver function panel (LFT)');
			$this->db->update('orderslist', $snomed8);
			$snomed9['snomed'] = '119252009';
			$this->db->where('orders_description','Pap smear with HPV testing');
			$this->db->update('orderslist', $snomed9);
			$this->db->where('orders_description','Pap smear');
			$this->db->update('orderslist', $snomed9);
			$snomed10['snomed'] = '143526001';
			$this->db->where('orders_description','Prostate specific antigen (PSA)');
			$this->db->update('orderslist', $snomed10);
			$snomed11['snomed'] = '166123004';
			$this->db->where('orders_description','Hepatitis C antibody');
			$this->db->update('orderslist', $snomed11);
			$snomed12['snomed'] = '19869000';
			$this->db->where('orders_description','RPR');
			$this->db->update('orderslist', $snomed12);
			$snomed13['snomed'] = '104130000';
			$this->db->where('orders_description','Peripheral smear');
			$this->db->update('orderslist', $snomed13);
			$snomed14['snomed'] = '273971007';
			$this->db->where('orders_description','Follicle stimulating hormone (FSH)');
			$this->db->update('orderslist', $snomed14);
			$snomed15['snomed'] = '69527006';
			$this->db->where('orders_description','Luteinizing hormone (LH)');
			$this->db->update('orderslist', $snomed15);
			$snomed16['snomed'] = '250660006';
			$this->db->where('orders_description','Follicle stimulating hormone and Leutinizing hormone (FSH and LH)');
			$this->db->update('orderslist', $snomed16);
			$snomed17['snomed'] = '399143002';
			$this->db->where('orders_description','Gonorrhea and Chlamydia GenProbe (GC/Chl PCR)');
			$this->db->update('orderslist', $snomed17);
			$snomed18['snomed'] = '313440008';
			$this->db->where('orders_description','Thyroid stimulating hormone (TSH)');
			$this->db->update('orderslist', $snomed18);
			$snomed19['snomed'] = '35650009';
			$this->db->where('orders_description','Thyroid panel (TSH, T3, Free T4)');
			$this->db->update('orderslist', $snomed19);
			$snomed20['snomed'] = '53853004';
			$this->db->where('orders_description','Urinalysis');
			$this->db->update('orderslist', $snomed20);
			$snomed21['snomed'] = '144792004';
			$this->db->where('orders_description','Urine culture');
			$this->db->update('orderslist', $snomed21);
			$snomed22['snomed'] = '';
			$this->db->where('orders_description','Wound culture');
			$this->db->update('orderslist', $snomed22);
			$snomed23['snomed'] = '388464003';
			$this->db->where('orders_description','Respiratory Allergen Testing');
			$this->db->update('orderslist', $snomed23);
			$snomed24['snomed'] = '117739006';
			$this->db->where('orders_description','Herpes Type 2 antibody');
			$this->db->update('orderslist', $snomed24);
			$snomed25['snomed'] = '32962002';
			$this->db->where('orders_description','CT of the abdomen with contrast');
			$this->db->update('orderslist', $snomed25);
			$snomed26['snomed'] = '169070004';
			$this->db->where('orders_description','CT of the abdomen without contrast');
			$this->db->update('orderslist', $snomed26);
			$snomed27['snomed'] = '75385009';
			$this->db->where('orders_description','CT of the chest with contrast');
			$this->db->update('orderslist', $snomed27);
			$snomed28['snomed'] = '169069000';
			$this->db->where('orders_description','CT of the chest without contrast');
			$this->db->update('orderslist', $snomed28);
			$snomed29['snomed'] = '396207002';
			$this->db->where('orders_description','CT of the head with contrast');
			$this->db->update('orderslist', $snomed29);
			$snomed30['snomed'] = '396205005';
			$this->db->where('orders_description','CT of the head without contrast');
			$this->db->update('orderslist', $snomed30);
			$snomed31['snomed'] = '431247005';
			$this->db->where('orders_description','CT of the sinuses');
			$this->db->update('orderslist', $snomed31);
			$snomed32['snomed'] = '431326009';
			$this->db->where('orders_description','CT of the neck with contrast');
			$this->db->update('orderslist', $snomed32);
			$snomed33['snomed'] = '169068008';
			$this->db->where('orders_description','CT of the neck without contrast');
			$this->db->update('orderslist', $snomed33);
			$snomed34['snomed'] = '300004007';
			$this->db->where('orders_description','DEXA scan');
			$this->db->update('orderslist', $snomed34);
			$snomed35['snomed'] = '275980005';
			$this->db->where('orders_description','Bilateral screening mammogram');
			$this->db->update('orderslist', $snomed35);
		}
		if (!$this->db->field_exists('schedule_increment','providers')) {
			$providers_definition = array(
				'schedule_increment' => array('type' => 'VARCHAR', 'constraint' => 100, 'default' => '20')
			);
			$this->dbforge->add_column('providers', $providers_definition);
			$providers_data = array(
				'schedule_increment' => '20'
			);
			$this->db->update('providers', $providers_data);
		}
		if (!$this->db->field_exists('encounter_role','encounters')) {
			$encounters_definition = array(
				'encounter_role' => array('type' => 'VARCHAR', 'constraint' => 255),
				'referring_provider' => array('type' => 'VARCHAR', 'constraint' => 255)
			);
			$this->dbforge->add_column('encounters', $encounters_definition);
			$encounters_data = array(
				'encounter_role' => 'Primary Care Provider'
			);
			$this->db->update('encounters', $encounters_data);
		}
		$template_array = array();
		$template_array[] = array(
			'category' => 'referral',
			'json' => '{"html":[{"type":"hidden","class":"ref_hidden","value":"Referral - Please provide primary physician with summaries of subsequent visits.","id":"ref_referral_hidden"},{"type":"checkbox","id":"ref_referral_1","class":"ref_other ref_intro","value":"Assume management for this particular problem and return patient after conclusion of care.","name":"ref_referral_1","caption":"Return patient after managing particular problem"},{"type":"br"},{"type":"checkbox","id":"ref_referral_2","class":"ref_other ref_intro","value":"Assume future management of patient within your area of expertise.","name":"ref_referral_2","caption":"Future ongoing management"},{"type":"br"},{"type":"checkbox","id":"ref_referral_3","class":"ref_other ref_after","value":"Please call me when you have seen the patient.","name":"ref_referral_3","caption":"Call back"},{"type":"br"},{"type":"checkbox","id":"ref_referral_4","class":"ref_other ref_after","value":"I would like to receive periodic status reports on this patient.","name":"ref_referral_4","caption":"Receive periodic status reports"},{"type":"br"},{"type":"checkbox","id":"ref_referral_5","class":"ref_other ref_after","value":"Please send a thorough written report when the consultation is complete.","name":"ref_referral_5","caption":"Receive thorough written report"}]}',
			'group' => 'referral',
			'sex' => 'm'
		);
		$template_array[] = array(
			'category' => 'referral',
			'json' => '{"html":[{"type":"hidden","class":"ref_hidden","value":"Referral - Please provide primary physician with summaries of subsequent visits.","id":"ref_referral_hidden"},{"type":"checkbox","id":"ref_referral_1","class":"ref_other ref_intro","value":"Assume management for this particular problem and return patient after conclusion of care.","name":"ref_referral_1","caption":"Return patient after managing particular problem"},{"type":"br"},{"type":"checkbox","id":"ref_referral_2","class":"ref_other ref_intro","value":"Assume future management of patient within your area of expertise.","name":"ref_referral_2","caption":"Future ongoing management"},{"type":"br"},{"type":"checkbox","id":"ref_referral_3","class":"ref_other ref_after","value":"Please call me when you have seen the patient.","name":"ref_referral_3","caption":"Call back"},{"type":"br"},{"type":"checkbox","id":"ref_referral_4","class":"ref_other ref_after","value":"I would like to receive periodic status reports on this patient.","name":"ref_referral_4","caption":"Receive periodic status reports"},{"type":"br"},{"type":"checkbox","id":"ref_referral_5","class":"ref_other ref_after","value":"Please send a thorough written report when the consultation is complete.","name":"ref_referral_5","caption":"Receive thorough written report"}]}',
			'group' => 'referral',
			'sex' => 'f'
		);
		$template_array[] = array(
			'category' => 'referral',
			'json' => '{"html":[{"type":"hidden","class":"ref_hidden","value":"Consultation - Please send the patient back for follow-up and treatment.","id":"ref_consultation_hidden"},{"type":"checkbox","id":"ref_consultation_1","class":"ref_other ref_intro","value":"Confirm the diagnosis.","name":"ref_consultation_1","caption":"Confirm the diagnosis"},{"type":"br"},{"type":"checkbox","id":"ref_consultation_2","class":"ref_other ref_intro","value":"Advise as to the diagnosis.","name":"ref_consultation_2","caption":"Advise as to the diagnosis"},{"type":"br"},{"type":"checkbox","id":"ref_consultation_3","class":"ref_other ref_intro","value":"Suggest medication or treatment for the diagnosis.","name":"ref_consultation_3","caption":"Suggest medication or treatment"},{"type":"br"},{"type":"checkbox","id":"ref_consultation_4","class":"ref_other ref_after","value":"Please call me when you have seen the patient.","name":"ref_consultation_4","caption":"Call back"},{"type":"br"},{"type":"checkbox","id":"ref_consultation_5","class":"ref_other ref_after","value":"I would like to receive periodic status reports on this patient.","name":"ref_consultation_5","caption":"Receive periodic status reports"},{"type":"br"},{"type":"checkbox","id":"ref_consultation_6","class":"ref_other ref_after","value":"Please send a thorough written report when the consultation is complete.","name":"ref_consultation_6","caption":"Receive thorough written report"}]}',
			'group' => 'consultation',
			'sex' => 'm'
		);
		$template_array[] = array(
			'category' => 'referral',
			'json' => '{"html":[{"type":"hidden","class":"ref_hidden","value":"Consultation - Please send the patient back for follow-up and treatment.","id":"ref_consultation_hidden"},{"type":"checkbox","id":"ref_consultation_1","class":"ref_other ref_intro","value":"Confirm the diagnosis.","name":"ref_consultation_1","caption":"Confirm the diagnosis"},{"type":"br"},{"type":"checkbox","id":"ref_consultation_2","class":"ref_other ref_intro","value":"Advise as to the diagnosis.","name":"ref_consultation_2","caption":"Advise as to the diagnosis"},{"type":"br"},{"type":"checkbox","id":"ref_consultation_3","class":"ref_other ref_intro","value":"Suggest medication or treatment for the diagnosis.","name":"ref_consultation_3","caption":"Suggest medication or treatment"},{"type":"br"},{"type":"checkbox","id":"ref_consultation_4","class":"ref_other ref_after","value":"Please call me when you have seen the patient.","name":"ref_consultation_4","caption":"Call back"},{"type":"br"},{"type":"checkbox","id":"ref_consultation_5","class":"ref_other ref_after","value":"I would like to receive periodic status reports on this patient.","name":"ref_consultation_5","caption":"Receive periodic status reports"},{"type":"br"},{"type":"checkbox","id":"ref_consultation_6","class":"ref_other ref_after","value":"Please send a thorough written report when the consultation is complete.","name":"ref_consultation_6","caption":"Receive thorough written report"}]}',
			'group' => 'consultation',
			'sex' => 'f'
		);
		$template_array[] = array(
			'category' => 'referral',
			'json' => '{"html":[{"type":"hidden","class":"ref_hidden","value":"Physical therapy referral details:","id":"ref_pt_hidden"},{"type":"div","class":"ref_buttonset","id":"ref_pt_1_div","html":[{"type":"span","html":"Objectives:"},{"type":"br"},{"type":"checkbox","id":"ref_pt_1a","class":"ref_other ref_intro","value":"Decrease pain.","name":"ref_pt_1","caption":"Decrease pain"},{"type":"checkbox","id":"ref_pt_1b","class":"ref_other ref_intro","value":"Increase strength.","name":"ref_pt_1","caption":"Increase strength"},{"type":"checkbox","id":"ref_pt_1c","class":"ref_other ref_intro","value":"Increase mobility.","name":"ref_pt_1","caption":"Increase mobility"}]},{"type":"br"},{"type":"div","class":"ref_buttonset","id":"ref_pt_2_div","html":[{"type":"span","html":"Modalities:"},{"type":"br"},{"type":"select","multiple":"multiple","id":"ref_pt_2","class":"ref_select ref_intro","css":{"width":"200px"},"name":"ref_pt_2","caption":"","options":{"Hot or cold packs. ":"Hot or cold packs.","TENS unit. ":"TENS unit.","Back program. ":"Back program.","Joint mobilization. ":"Joint mobilization.","Home program. ":"Home program.","Pool therapy. ":"Pool therapy.","Feldenkrais method. ":"Feldenkrais method.","Therapeutic exercise. ":"Therapeutic exercise.","Myofascial release. ":"Myofascial release.","Patient education. ":"Patient education.","Work hardening. ":"Work hardening."}}]},{"type":"br"},{"type":"text","id":"ref_pt_3","css":{"width":"200px"},"class":"ref_other ref_detail_text ref_intro","name":"ref_pt_3","placeholder":"Precautions"},{"type":"br"},{"type":"text","id":"ref_pt_4","css":{"width":"200px"},"class":"ref_other ref_detail_text ref_intro","name":"ref_pt_4","placeholder":"Frequency"},{"type":"br"},{"type":"text","id":"ref_pt_5","css":{"width":"200px"},"class":"ref_other ref_detail_text ref_intro","name":"ref_pt_5","placeholder":"Duration"}]}',
			'group' => 'pt',
			'sex' => 'm'
		);
		$template_array[] = array(
			'category' => 'referral',
			'json' => '{"html":[{"type":"hidden","class":"ref_hidden","value":"Physical therapy referral details:","id":"ref_pt_hidden"},{"type":"div","class":"ref_buttonset","id":"ref_pt_1_div","html":[{"type":"span","html":"Objectives:"},{"type":"br"},{"type":"checkbox","id":"ref_pt_1a","class":"ref_other ref_intro","value":"Decrease pain.","name":"ref_pt_1","caption":"Decrease pain"},{"type":"checkbox","id":"ref_pt_1b","class":"ref_other ref_intro","value":"Increase strength.","name":"ref_pt_1","caption":"Increase strength"},{"type":"checkbox","id":"ref_pt_1c","class":"ref_other ref_intro","value":"Increase mobility.","name":"ref_pt_1","caption":"Increase mobility"}]},{"type":"br"},{"type":"div","class":"ref_buttonset","id":"ref_pt_2_div","html":[{"type":"span","html":"Modalities:"},{"type":"br"},{"type":"select","multiple":"multiple","id":"ref_pt_2","class":"ref_select ref_intro","css":{"width":"200px"},"name":"ref_pt_2","caption":"","options":{"Hot or cold packs. ":"Hot or cold packs.","TENS unit. ":"TENS unit.","Back program. ":"Back program.","Joint mobilization. ":"Joint mobilization.","Home program. ":"Home program.","Pool therapy. ":"Pool therapy.","Feldenkrais method. ":"Feldenkrais method.","Therapeutic exercise. ":"Therapeutic exercise.","Myofascial release. ":"Myofascial release.","Patient education. ":"Patient education.","Work hardening. ":"Work hardening."}}]},{"type":"br"},{"type":"text","id":"ref_pt_3","css":{"width":"200px"},"class":"ref_other ref_detail_text ref_intro","name":"ref_pt_3","placeholder":"Precautions"},{"type":"br"},{"type":"text","id":"ref_pt_4","css":{"width":"200px"},"class":"ref_other ref_detail_text ref_intro","name":"ref_pt_4","placeholder":"Frequency"},{"type":"br"},{"type":"text","id":"ref_pt_5","css":{"width":"200px"},"class":"ref_other ref_detail_text ref_intro","name":"ref_pt_5","placeholder":"Duration"}]}',
			'group' => 'pt',
			'sex' => 'f'
		);
		$template_array[] = array(
			'category' => 'referral',
			'json' => '{"html":[{"type":"hidden","class":"ref_hidden","value":"Massage therapy referral details:","id":"ref_massage_hidden"},{"type":"div","class":"ref_buttonset","id":"ref_massage_1_div","html":[{"type":"span","html":"Objectives:"},{"type":"br"},{"type":"checkbox","id":"ref_massage_1a","class":"ref_other ref_intro","value":"Decrease pain.","name":"ref_massage_1","caption":"Decrease pain"},{"type":"checkbox","id":"ref_massage_1b","class":"ref_other ref_intro","value":"Increase mobility.","name":"ref_massage_1","caption":"Increase mobility"}]},{"type":"br"},{"type":"text","id":"ref_massage_2","css":{"width":"200px"},"class":"ref_other ref_detail_text ref_intro","name":"ref_massage_2","placeholder":"Precautions"},{"type":"br"},{"type":"text","id":"ref_massage_3","css":{"width":"200px"},"class":"ref_other ref_detail_text ref_intro","name":"ref_massage_3","placeholder":"Frequency"},{"type":"br"},{"type":"text","id":"ref_massage_4","css":{"width":"200px"},"class":"ref_other ref_detail_text ref_intro","name":"ref_massage_4","placeholder":"Duration"}]}',
			'group' => 'massage',
			'sex' => 'm'
		);
		$template_array[] = array(
			'category' => 'referral',
			'json' => '{"html":[{"type":"hidden","class":"ref_hidden","value":"Massage therapy referral details:","id":"ref_massage_hidden"},{"type":"div","class":"ref_buttonset","id":"ref_massage_1_div","html":[{"type":"span","html":"Objectives:"},{"type":"br"},{"type":"checkbox","id":"ref_massage_1a","class":"ref_other ref_intro","value":"Decrease pain.","name":"ref_massage_1","caption":"Decrease pain"},{"type":"checkbox","id":"ref_massage_1b","class":"ref_other ref_intro","value":"Increase mobility.","name":"ref_massage_1","caption":"Increase mobility"}]},{"type":"br"},{"type":"text","id":"ref_massage_2","css":{"width":"200px"},"class":"ref_other ref_detail_text ref_intro","name":"ref_massage_2","placeholder":"Precautions"},{"type":"br"},{"type":"text","id":"ref_massage_3","css":{"width":"200px"},"class":"ref_other ref_detail_text ref_intro","name":"ref_massage_3","placeholder":"Frequency"},{"type":"br"},{"type":"text","id":"ref_massage_4","css":{"width":"200px"},"class":"ref_other ref_detail_text ref_intro","name":"ref_massage_4","placeholder":"Duration"}]}',
			'group' => 'massage',
			'sex' => 'f'
		);
		$template_array[] = array(
			'category' => 'referral',
			'json' => '{"html":[{"type":"hidden","class":"ref_hidden","value":"Sleep study referral details:","id":"ref_sleep_study_hidden"},{"type":"div","class":"ref_buttonset","id":"ref_sleep_study_1_div","html":[{"type":"span","html":"Type:"},{"type":"br"},{"type":"select","multiple":"multiple","id":"ref_sleep_study_1","class":"ref_select ref_other ref_intro","css":{"width":"200px"},"name":"ref_sleep_study_1","caption":"","options":{"Diagnostic Sleep Study Only.\n":"Diagnostic Sleep Study Only.","Diagnostic testing with Continuous Positive Airway Pressure.\n":"Diagnostic testing with Continuous Positive Airway Pressure.","Diagnostic testing with BiLevel Positive Airway Pressure.\n":"Diagnostic testing with BiLevel Positive Airway Pressure.","Diagnostic testing with BiLevel Positive Airway Pressure.\n":"Diagnostic testing with BiLevel Positive Airway Pressure.","Diagnostic testing with Oxygen.\n":"Diagnostic testing with Oxygen.","Diagnostic testing with Oral Device.\n":"Diagnostic testing with Oral Device.","MSLT (Multiple Sleep Latency Test).\n":"MSLT (Multiple Sleep Latency Test).","MWT (Maintenance of Wakefulness Test).\n":"MWT (Maintenance of Wakefulness Test).","Titrate BiPAP settings.\n":"Titrate BiPAP settings.","Patient education. ":"Patient education.","Work hardening. ":"Work hardening."}}]},{"type":"br"},{"type":"div","class":"ref_buttonset","id":"ref_sleep_study_2_div","html":[{"type":"span","html":"BiPAP pressures:"},{"type":"br"},{"type":"text","id":"ref_sleep_study_2a","css":{"width":"200px"},"class":"ref_other ref_detail_text ref_intro","name":"ref_sleep_study_2a","placeholder":"Inspiratory Pressure (IPAP), cm H20"},{"type":"br"},{"type":"text","id":"ref_sleep_study_2b","css":{"width":"200px"},"class":"ref_other ref_detail_text ref_intro","name":"ref_sleep_study_2b","placeholder":"Expiratory Pressure (EPAP), cm H20"}]},{"type":"br"},{"type":"div","class":"ref_buttonset","id":"ref_sleep_study_3_div","html":[{"type":"span","html":"BiPAP Mode:"},{"type":"br"},{"type":"checkbox","id":"ref_sleep_study_3a","class":"ref_other ref_intro","value":"Spontaneous mode.","name":"ref_sleep_study_3","caption":"Spontaneous"},{"type":"checkbox","id":"ref_sleep_study_3b","class":"ref_other ref_intro","value":"Spontaneous/Timed mode","name":"ref_sleep_study_3","caption":"Spontaneous/Timed"},{"type":"br"},{"type":"text","id":"ref_sleep_study_3c","css":{"width":"200px"},"class":"ref_other ref_detail_text ref_intro","name":"ref_sleep_study_3","placeholder":"Breaths per minute"}]}]}',
			'group' => 'sleep_study',
			'sex' => 'm'
		);
		$template_array[] = array(
			'category' => 'referral',
			'json' => '{"html":[{"type":"hidden","class":"ref_hidden","value":"Sleep study referral details:","id":"ref_sleep_study_hidden"},{"type":"div","class":"ref_buttonset","id":"ref_sleep_study_1_div","html":[{"type":"span","html":"Type:"},{"type":"br"},{"type":"select","multiple":"multiple","id":"ref_sleep_study_1","class":"ref_select ref_other ref_intro","css":{"width":"200px"},"name":"ref_sleep_study_1","caption":"","options":{"Diagnostic Sleep Study Only.\n":"Diagnostic Sleep Study Only.","Diagnostic testing with Continuous Positive Airway Pressure.\n":"Diagnostic testing with Continuous Positive Airway Pressure.","Diagnostic testing with BiLevel Positive Airway Pressure.\n":"Diagnostic testing with BiLevel Positive Airway Pressure.","Diagnostic testing with BiLevel Positive Airway Pressure.\n":"Diagnostic testing with BiLevel Positive Airway Pressure.","Diagnostic testing with Oxygen.\n":"Diagnostic testing with Oxygen.","Diagnostic testing with Oral Device.\n":"Diagnostic testing with Oral Device.","MSLT (Multiple Sleep Latency Test).\n":"MSLT (Multiple Sleep Latency Test).","MWT (Maintenance of Wakefulness Test).\n":"MWT (Maintenance of Wakefulness Test).","Titrate BiPAP settings.\n":"Titrate BiPAP settings.","Patient education. ":"Patient education.","Work hardening. ":"Work hardening."}}]},{"type":"br"},{"type":"div","class":"ref_buttonset","id":"ref_sleep_study_2_div","html":[{"type":"span","html":"BiPAP pressures:"},{"type":"br"},{"type":"text","id":"ref_sleep_study_2a","css":{"width":"200px"},"class":"ref_other ref_detail_text ref_intro","name":"ref_sleep_study_2a","placeholder":"Inspiratory Pressure (IPAP), cm H20"},{"type":"br"},{"type":"text","id":"ref_sleep_study_2b","css":{"width":"200px"},"class":"ref_other ref_detail_text ref_intro","name":"ref_sleep_study_2b","placeholder":"Expiratory Pressure (EPAP), cm H20"}]},{"type":"br"},{"type":"div","class":"ref_buttonset","id":"ref_sleep_study_3_div","html":[{"type":"span","html":"BiPAP Mode:"},{"type":"br"},{"type":"checkbox","id":"ref_sleep_study_3a","class":"ref_other ref_intro","value":"Spontaneous mode.","name":"ref_sleep_study_3","caption":"Spontaneous"},{"type":"checkbox","id":"ref_sleep_study_3b","class":"ref_other ref_intro","value":"Spontaneous/Timed mode","name":"ref_sleep_study_3","caption":"Spontaneous/Timed"},{"type":"br"},{"type":"text","id":"ref_sleep_study_3c","css":{"width":"200px"},"class":"ref_other ref_detail_text ref_intro","name":"ref_sleep_study_3","placeholder":"Breaths per minute"}]}]}',
			'group' => 'sleep_study',
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
		if (!$this->db->field_exists('insurance_box_31','addressbook')) {
			$addressbook_definition = array(
				'insurance_box_31' => array('type' => 'VARCHAR', 'constraint' => 4),
				'insurance_box_32a' => array('type' => 'VARCHAR', 'constraint' => 4)
			);
			$this->dbforge->add_column('addressbook', $addressbook_definition);
			$addressbook_data = array(
				'insurance_box_31' => 'n',
				'insurance_box_32a' => 'n'
			);
			$this->db->where('specialty', "Insurance");
			$this->db->update('addressbook',$addressbook_data);
		}
		$version_data = array(
			'version' => '1.7.0',
			'vivacare' => ''
		);
		$this->db->update('practiceinfo', $version_data);
	}
	
	function system_update1_7_1()
	{
		$this->load->dbforge();
		$this->load->database();
		$tags_definition = array(
			'tags_id' => array('type' => 'INT', 'constraint' => 40, 'auto_increment' => TRUE),
			'tag' => array('type' => 'VARCHAR', 'constraint' => 255)
		);
		$this->dbforge->add_field($tags_definition);
		$this->dbforge->add_key('tags_id', TRUE);
		$this->dbforge->create_table('tags', TRUE);
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
			'mtm_id' => array('type' => 'INT', 'constraint' => 40)
		);
		$this->dbforge->add_field($tags_relate_definition);
		$this->dbforge->add_key('tags_relate_id', TRUE);
		$this->dbforge->create_table('tags_relate', TRUE);
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
		$this->db->where('group_id', '1');
		$admin_row = $this->db->get('users')->row_array();
		$admin_data['active'] = '1';
		$this->db->where('id', $admin_row['id']);
		$this->db->update('users', $admin_data);
		$practiceinfo = $this->db->get('practiceinfo')->row_array();
		$scans_directory = $practiceinfo['documents_dir'] . "scans";
		chmod($scans_directory, 0777);
		$version_data = array(
			'version' => '1.7.1'
		);
		$this->db->update('practiceinfo', $version_data);
	}
	
	function run()
	{
		$this->load->dbforge();
		$this->load->database();
	}
}

/* End of file front.php */
/* Location: ./system/application/controllers/front.php */
