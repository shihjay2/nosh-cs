<?php
class Fax_model extends Model {

	function Fax_model()
	{
		parent::Model();
		$this->obj =& get_instance();
	}

	// --------------------------------------------------------------------

	function fax_type()
	{
		$query = $this->db->get('practiceinfo');
		$result = $query->row_array();
		return $result['fax_type'];
	}
	
	function addFax($fax_data)
	{
		if ($this->db->insert('sendfax', $fax_data)) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	function addSendList($send_list_data)
	{
		if ($this->db->insert('recipients', $send_list_data)) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	function addPages($pages_data)
	{
		if ($this->db->insert('pages', $pages_data)) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	// --------------------------------------------------------------------
	
	function getFax($job_id)
	{
		$this->db->where('job_id', $job_id);
		return $this->db->get('sendfax');
	}
	
	function getSendList($sendlist_id)
	{
		$this->db->where('sendlist_id', $sendlist_id);
		return $this->db->get('recipients');
	}

	function getRecipientList($job_id)
	{
		$this->db->select('faxrecipient, faxnumber');
		$this->db->where('job_id', $job_id);
		return $this->db->get('recipients');
	}

	function getPages($job_id)
	{
		return $this->db->query("SELECT * FROM pages WHERE job_id=$job_id");
	}
	
	function getPages1($job_id, $sidx, $sord, $start, $limit)
	{
		return $this->db->query("SELECT * FROM pages WHERE job_id=$job_id ORDER BY $sidx $sord LIMIT $start , $limit");
	}
	
	function getPages2($pages_id)
	{
		$this->db->select('file');
		$this->db->where('pages_id', $pages_id);
		return $this->db->get('pages');
	}
	
	// --------------------------------------------------------------------
	
	function updateFax($job_id, $fax_data)
	{
		$this->db->where('job_id', $job_id);
		if ($this->db->update('sendfax', $fax_data)) {
			return $job_id;
		} else {
			return FALSE;
		}
	}
	
	function updateSendList($sendlist_id, $send_list_data)
	{
		$this->db->where('sendlist_id', $sendlist_id);
		$this->db->update('recipients', $send_list_data);
	}

	function insertSentDate($date, $job_id)
	{
		$this->db->where('job_id', $job_id);
		if ($this->db->update('sendfax', $date)) {
			return $job_id;
		} else {
			return FALSE;
		}
	}
	
	// --------------------------------------------------------------------
	
	function deleteFax($job_id)
	{
		$this->db->where('job_id', $job_id);
		$this->db->delete('sendfax');
	}

	function deleteSendList($sendlist_id)
	{
		$this->db->where('sendlist_id', $sendlist_id);
		$this->db->delete('recipients');
	}
	
	function deletePage($pages_id)
	{
		$this->db->where('pages_id', $pages_id);
		$this->db->delete('pages');
	}
	
	// --------------------------------------------------------------------
	
	function pagecount($filename)
	{
		$pdftext = file_get_contents($filename);
  		$pagecount = preg_match_all("/\/Page\W/", $pdftext, $dummy);
		return $pagecount;
	}
}
/* End of file fax_model.php */
/* Location: ./system/application/models/fax_model.php */
