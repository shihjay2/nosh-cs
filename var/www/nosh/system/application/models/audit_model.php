<?php
class Audit_model extends Model {

	function Audit_model()
	{
		parent::Model();
		$this->obj =& get_instance();
	}

	// --------------------------------------------------------------------

	function add()
	{
		$audit = array(
			'user_id' => $this->session->userdata('user_id'),
			'displayname' => $this->session->userdata('displayname'),
			'pid' => $this->session->userdata('pid'),
			'group_id' =>  $this->session->userdata('group_id'),
			'action' => 'Add',
			'query' => $this->db->last_query(),
			'practice_id' => $this->session->userdata('practice_id')
		);
		$this->db->insert('audit', $audit);
		
	}
	
	// --------------------------------------------------------------------
	
	function update()
	{
		$audit = array(
			'user_id' => $this->session->userdata('user_id'),
			'displayname' => $this->session->userdata('displayname'),
			'pid' => $this->session->userdata('pid'),
			'group_id' =>  $this->session->userdata('group_id'),
			'action' => 'Update',
			'query' => $this->db->last_query(),
			'practice_id' => $this->session->userdata('practice_id')
		);
		$this->db->insert('audit', $audit);
	}
	
	// --------------------------------------------------------------------
	
	function delete()
	{
		$audit = array(
			'user_id' => $this->session->userdata('user_id'),
			'displayname' => $this->session->userdata('displayname'),
			'pid' => $this->session->userdata('pid'),
			'group_id' =>  $this->session->userdata('group_id'),
			'action' => 'Delete',
			'query' => $this->db->last_query(),
			'practice_id' => $this->session->userdata('practice_id')
		);
		$this->db->insert('audit', $audit);
	}
}

/* End of file audit_model.php */
/* Location: ./system/application/models/audit_model.php */
