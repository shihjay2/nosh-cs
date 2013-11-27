<?php
class Schedule_model extends Model {

	function Schedule_model()
	{
		parent::Model();
		$this->obj =& get_instance();
	}

	// --------------------------------------------------------------------

	function get_calendar_settings()
	{
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
		return $this->db->get('calendar');
	}
	
	function getNumberAppts($id)
	{
		$start_time = strtotime("today 00:00:00");
		$end_time = $start_time + 86400;
		$query = $this->db->query("SELECT * FROM schedule WHERE provider_id=$id AND start BETWEEN $start_time AND $end_time");
		return $query->num_rows();
	}
				
	
	// --------------------------------------------------------------------
	
	function add_visit_type($data)
	{
		if ($this->db->insert('calendar', $data)) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	function update_visit_type($id, $data)
	{
		$this->db->insert('calendar', $data);
		$data1 = array(
			'active' => 'n'
		);
		$this->db->where('calendar_id', $id);
		$this->db->update('calendar', $data1);
	}
	
	function del_visit_type($id)
	{
		$data1 = array(
			'active' => 'n'
		);
		$this->db->where('calendar_id', $id);
		$this->db->update('calendar', $data1);
	}
	
	// --------------------------------------------------------------------
	
	function add_event($data)
	{
		if ($this->db->insert('schedule', $data))
		{
			return $this->db->insert_id();
		}
		else
		{
			return FALSE;
		}
	}
	
	function update_event($id, $data)
	{
		$this->db->where('appt_id', $id);
		$this->db->update('schedule', $data);
	}
	
	function del_event($id)
	{
		$this->db->where('appt_id', $id);
		$this->db->delete('schedule'); 
	}
		
	// --------------------------------------------------------------------
	
	function add_repeat($data)
	{
		if ($this->db->insert('repeat_schedule', $data))
		{
			return $this->db->insert_id();
		}
		else
		{
			return FALSE;
		}
	}
	
	function update_repeat($id, $data)
	{
		$this->db->where('repeat_id', $id);
		$this->db->update('repeat_schedule', $data);
	}
	
	function del_repeat($id)
	{
		$this->db->where('repeat_id', $id);
		$this->db->delete('repeat_schedule'); 
	}
}

/* End of file schedule_model.php */
/* Location: ./system/application/models/schedule_model.php */
