<?php
class Office_model extends Model {

	function Office_model()
	{
		parent::Model();
		$this->obj =& get_instance();
	}

	// --------------------------------------------------------------------

	function addVaccineInventory($data)
	{
		if ($this->db->insert('vaccine_inventory', $data)) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	function updateVaccineInventory($vaccine_id, $data)
	{
		$this->db->where('vaccine_id', $vaccine_id);
		$this->db->update('vaccine_inventory', $data);
	}
	
	function deleteVaccineInventory($vaccine_id)
	{
		$this->db->where('vaccine_id', $vaccine_id);
		$this->db->delete('vaccine_inventory');
	}
	
	// --------------------------------------------------------------------

	function addSupplementInventory($data)
	{
		if ($this->db->insert('supplement_inventory', $data)) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	function updateSupplementInventory($supplement_id, $data)
	{
		$this->db->where('supplement_id', $supplement_id);
		$this->db->update('supplement_inventory', $data);
	}
	
	function deleteSupplementInventory($supplement_id)
	{
		$this->db->where('supplement_id', $supplement_id);
		$this->db->delete('supplement_inventory');
	}
	
	// --------------------------------------------------------------------
	
	function addVaccineTemp($data)
	{
		if ($this->db->insert('vaccine_temp', $data)) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	function updateVaccineTemp($vaccine_id, $data)
	{
		$this->db->where('temp_id', $vaccine_id);
		$this->db->update('vaccine_temp', $data);
	}
	
	function deleteVaccineTemp($vaccine_id)
	{
		$this->db->where('temp_id', $vaccine_id);
		$this->db->delete('vaccine_temp');
	}
	
	function getEncounter($eid)
	{
		$this->db->where('eid', $eid);
		return $this->db->get('encounters');
	}
}

/* End of file office_model.php */
/* Location: ./system/application/models/office_model.php */
