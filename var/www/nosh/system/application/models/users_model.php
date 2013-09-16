<?php

class Users_model extends Model {

	function Users_model()
	{
   		parent::Model();
	}

	function add($data)
	{
		if ($this->db->insert('users', $data))
		{
			return $this->db->insert_id();
		}
		else
		{
			return FALSE;
		}
	}
	
	function addProvider($id, $data)
	{
		$data1 = array(
			'id' => $id
		);
		$this->db->insert('providers', $data1);
		$this->db->where('id', $id);
		$this->db->update('providers', $data);
	}
	function update($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update('users', $data);
	}
	
	function updateProvider($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update('providers', $data);
	}
	
	function get($id)
	{
		$this->db->where('id', $id);
		return $this->db->get('users');
	}
	
	function getProviders()
	{
		$this->db->select('id, displayname');
		$this->db->where('group_id', '2');
		return $this->db->get('users');
	}
}

/* End of file users_model.php */
/* Location: ./system/application/models/users_model.php */
