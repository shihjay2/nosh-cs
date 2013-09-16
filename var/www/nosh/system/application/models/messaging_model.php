<?php

class Messaging_model extends Model {

	function Messaging_model()
	{
   		parent::Model();
	}

	function add($data)
	{
		if ($this->db->insert('messaging', $data))
		{
			return $this->db->insert_id();
		}
		else
		{
			return FALSE;
		}
	}
	
	function update($id, $data)
	{
		$this->db->where('message_id', $id);
		$this->db->update('messaging', $data);
	}
	
	function delete($id)
	{
		$this->db->where('message_id', $id);
		$this->db->delete('messaging');
	}
	
	function addscan($data)
	{
		if ($this->db->insert('scans', $data))
		{
			return $this->db->insert_id();
		}
		else
		{
			return FALSE;
		}
	}
	
}

/* End of file messaging_model.php */
/* Location: ./system/application/models/messaging_model.php */
