<?php
class Template_model extends Model {

	function Template_model()
	{
		parent::Model();
		$this->obj =& get_instance();
	}

	// --------------------------------------------------------------------
	
	function add_familyhx($data)
	{
		if ($this->db->insert('familyhxlist', $data))
		{
			return $this->db->insert_id();
		}
		else
		{
			return FALSE;
		}
	}
	
	function update_familyhx($id, $data)
	{
		$this->db->where('familyhxlist_id', $id);
		$this->db->update('familyhxlist', $data);
	}
	
	function del_familyhx($id)
	{
		$this->db->where('familyhxlist_id', $id);
		$this->db->delete('familyhxlist'); 
	}
	
	// --------------------------------------------------------------------
	
	function add_procedurelist($data)
	{
		if ($this->db->insert('procedurelist', $data))
		{
			return $this->db->insert_id();
		}
		else
		{
			return FALSE;
		}
	}
	
	function update_procedurelist($id, $data)
	{
		$this->db->where('procedurelist_id', $id);
		$this->db->update('procedurelist', $data);
	}
	
	function del_procedurelist($id)
	{
		$this->db->where('procedurelist_id', $id);
		$this->db->delete('procedurelist'); 
	}
	
	// --------------------------------------------------------------------
	
	function add_orderslist($data)
	{
		if ($this->db->insert('orderslist', $data))
		{
			return $this->db->insert_id();
		}
		else
		{
			return FALSE;
		}
	}
	
	function update_orderslist($id, $data)
	{
		$this->db->where('orderslist_id', $id);
		$this->db->update('orderslist', $data);
	}
	
	function del_orderslist($id)
	{
		$this->db->where('orderslist_id', $id);
		$this->db->delete('orderslist'); 
	}
}
/* End of file template_model.php */
/* Location: ./system/application/models/template_model.php */
