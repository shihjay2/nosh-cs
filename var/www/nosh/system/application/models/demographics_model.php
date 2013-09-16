<?php

class Demographics_model extends Model {

	function Demographics_model()
	{
   		parent::Model();
	}

	function add($data)
	{
		if ($this->db->insert('demographics', $data))
		{
			return $this->db->insert_id();
		}
		else
		{
			return FALSE;
		}
	}
	
	function update($pid, $data)
	{
		$this->db->where('pid', $pid);
		$this->db->update('demographics', $data);
	}
	
	function delete($pid)
	{
		$this->db->where('pid', $pid);
		$this->db->delete('demographics', $data);
	}
	
	function get($pid)
	{
		$this->db->where('pid', $pid);
		return $this->db->get('demographics');
	}
}

/* End of file demographics_model.php */
/* Location: ./system/application/models/demographics_model.php */
