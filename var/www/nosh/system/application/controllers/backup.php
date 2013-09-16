<?php

class Backup extends Application
{

	function Backup()
	{
		parent::Application();
		$this->load->database();
		$this->load->helper('file');
		$this->load->helper('date');
	}

	// --------------------------------------------------------------------

	function index()
	{
		include(APPPATH.'config/database'.EXT);
		$query2 = $this->db->query("SELECT * FROM practiceinfo WHERE practice_id=1");
		foreach ($query2->result_array() as $row2) {
			$dir = $row2['documents_dir'];
		}
		$file = $dir . "noshbackup_" . now() . ".sql";
		$command = "mysqldump -u " . $db['default']['username']. " -p". $db['default']['password'] . " nosh > " . $file;
		system($command);
		$files = glob($dir . "*.sql");
		foreach ($files as $file_row) {
			$explode = explode("_", $file_row);
			$time = intval(str_replace(".sql","",$explode[1]));
			$month = now() - 604800;
			if ($time < $month) {
				unlink($file_row);
			}
		}
		$extensions_prune = $this->db->query("DELETE FROM extensions_log WHERE DATE_SUB(CURDATE(), INTERVAL 30 DAY) >= timestamp");
		exit (0);
	}
	
	function find_backups()
	{
		$query2 = $this->db->query("SELECT * FROM practiceinfo WHERE practice_id=1");
		foreach ($query2->result_array() as $row2) {
			$dir = $row2['documents_dir'];
		}
		$files = glob($dir . "*.sql");
		$data['options'] = array();
		arsort($files);
		foreach ($files as $file) {
			$explode = explode("_", $file);
			$time = intval(str_replace(".sql","",$explode[1]));
			$data['options'][$file] = unix_to_human($time);
		}
		echo json_encode($data);
	}
	
	function restore()
	{
		$file = $this->input->post('file');
		include(APPPATH.'config/database'.EXT);
		$command = "mysql -u " . $db['default']['username']. " -p". $db['default']['password'] . " nosh < " . $file;
		system($command);
		echo "Database restored!";
		exit (0);
	}
}
/* End of file: backup.php */
/* Location: application/controllers/backup.php */
