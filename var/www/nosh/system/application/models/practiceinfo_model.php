<?php
class Practiceinfo_model extends Model {

	function Practiceinfo_model()
	{
		parent::Model();
		$this->obj =& get_instance();
	}

	// --------------------------------------------------------------------

	function get($id)
	{
		$this->db->where('practice_id', $id);
		return $this->db->get('practiceinfo');
	}
	
	function get_calendar_settings($id)
	{
		$this->db->where('practice_id', $id);
		return $this->db->get('calendar');
	}
	
	function getProviders($id)
	{
		$this->db->where('group_id', '2');
		$this->db->where('practice_id', $id);
		return $this->db->get('users');
	}
	
	function get_extensions($id)
	{
		$this->db->select('rcopia_extension');
		$this->db->select('rcopia_apiVendor');
		$this->db->select('rcopia_apiPractice');
		$this->db->select('rcopia_apiPass');
		$this->db->select('rcopia_apiSystem');
		$this->db->select('updox_extension');
		$this->db->select('mtm_extension');
		$this->db->select('mtm_alert_users');
		$this->db->select('snomed_extension');
		$this->db->select('vivacare');
		$this->db->select('peacehealth_id');
		$this->db->where('practice_id', $id);
		return $this->db->get('practiceinfo');
	}
}

/* End of file practiceinfo_model.php */
/* Location: ./system/application/models/practiceinfo_model.php */
