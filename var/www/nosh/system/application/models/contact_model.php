<?php
class Contact_model extends Model {

	function Contact_model()
	{
		parent::Model();
		$this->obj =& get_instance();
	}

	// --------------------------------------------------------------------

	function addContact($contact_data)
	{
		if ($this->db->insert('addressbook', $contact_data)) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	// --------------------------------------------------------------------
	
	function updateContact($address_id, $contact_data)
	{
		$this->db->where('address_id', $address_id);
		$this->db->update('addressbook', $contact_data);
	}
	
	// --------------------------------------------------------------------
	
	function deleteContact($address_id)
	{
		$this->db->where('address_id', $address_id);
		$this->db->delete('addressbook');
	}
}

/* End of file contact_model.php */
/* Location: ./system/application/models/contact_model.php */
