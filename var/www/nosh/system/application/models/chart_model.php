<?php
class Chart_model extends Model {

	function Chart_model()
	{
		parent::Model();
		$this->obj =& get_instance();
	}

	// --------------------------------------------------------------------

	function addTMessage($data)
	{
		if ($this->db->insert('t_messages', $data)) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	function updateTMessage($id, $data)
	{
		$this->db->where('t_messages_id', $id);
		$this->db->update('t_messages', $data);
	}
	
	function deleteTMessage($id)
	{
		$this->db->where('t_messages_id', $id);
		$this->db->delete('t_messages');
	}
	
	function getTMessage($id)
	{
		$this->db->where('t_messages_id', $id);
		return $this->db->get('t_messages');
	}
	
	function addIssue($data)
	{
		if ($this->db->insert('issues', $data)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function updateIssue($id, $data)
	{
		$this->db->where('issue_id', $id);
		$this->db->update('issues', $data);
	}
	
	function deleteIssue($id)
	{
		$this->db->where('issue_id', $id);
		$this->db->delete('issues');
	}
	
	function addMedication($data)
	{
		if ($this->db->insert('rx_list', $data)) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	function updateMedication($id, $data)
	{
		$this->db->where('rxl_id', $id);
		$this->db->update('rx_list', $data);
	}	
	
	function deleteMedication($id)
	{
		$this->db->where('rxl_id', $id);
		$this->db->delete('rx_list');
	}
	
	function addSupplement($data)
	{
		if ($this->db->insert('sup_list', $data)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function updateSupplement($id, $data)
	{
		$this->db->where('sup_id', $id);
		$this->db->update('sup_list', $data);
	}	
	
	function deleteSupplement($id)
	{
		$this->db->where('sup_id', $id);
		$this->db->delete('sup_list');
	}
	
	function addAllergy($data)
	{
		if ($this->db->insert('allergies', $data)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function updateAllergy($id, $data)
	{
		$this->db->where('allergies_id', $id);
		$this->db->update('allergies', $data);
	}	
	
	function deleteAllergy($id)
	{
		$this->db->where('allergies_id', $id);
		$this->db->delete('allergies');
	}
	
	function addImmunization($data)
	{
		if ($this->db->insert('immunizations', $data)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function updateImmunization($id, $data)
	{
		$this->db->where('imm_id', $id);
		$this->db->update('immunizations', $data);
	}	
	
	function deleteImmunization($id)
	{
		$this->db->where('imm_id', $id);
		$this->db->delete('immunizations');
	}
	
	function addAlert($data)
	{
		if ($this->db->insert('alerts', $data)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function updateAlert($id, $data)
	{
		$this->db->where('alert_id', $id);
		$this->db->update('alerts', $data);
	}	
	
	function deleteAlert($id)
	{
		$this->db->where('alert_id', $id);
		$this->db->delete('alerts');
	}
	
	function addOrdersList($data)
	{
		if ($this->db->insert('orderslist', $data)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function updateOrdersList($id, $data)
	{
		$this->db->where('orderslist_id', $id);
		$this->db->update('orderslist', $data);
	}
	
	function addOrder($data)
	{
		if ($this->db->insert('orders', $data)) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	function updateOrder($id, $data)
	{
		$this->db->where('orders_id', $id);
		$this->db->update('orders', $data);
	}
	
	function deleteOrder($id)
	{
		$this->db->where('orders_id', $id);
		$this->db->delete('orders');
	}
	
	function addInsurance($data)
	{
		if ($this->db->insert('insurance', $data)) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	function updateInsurance($id, $data)
	{
		$this->db->where('insurance_id', $id);
		$this->db->update('insurance', $data);
	}	
	
	function deleteInsurance($id)
	{
		$this->db->where('insurance_id', $id);
		$this->db->delete('insurance');
	}
	
	function getActiveInsurance($pid)
	{
		$this->db->where('pid', $pid);
		$this->db->where('insurance_plan_active', 'Yes');
		return $this->db->get('insurance');
	}
	
	function getDocuments($id)
	{
		$this->db->where('documents_id', $id);
		return $this->db->get('documents');
	}
	
	function addDocuments($data)
	{
		if ($this->db->insert('documents', $data)) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	function updateDocuments($id, $data)
	{
		$this->db->where('documents_id', $id);
		$this->db->update('documents', $data);
	}
	
	function deleteDocuments($id)
	{
		$this->db->where('documents_id', $id);
		$this->db->delete('documents');
	}
	
	function addBillingCore($billing_data)
	{
		if ($this->db->insert('billing_core', $billing_data)) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	function updateBillingCore($id, $billing_data)
	{
		$this->db->where('billing_core_id', $id);
		$this->db->update('billing_core', $billing_data);
	}
	
	function deleteBillingCore($id)
	{
		$this->db->where('billing_core_id', $id);
		$this->db->delete('billing_core');
	}
	
	function addHippa($hippa_data)
	{
		if ($this->db->insert('hippa', $hippa_data)) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	function updateHippa($id, $hippa_data)
	{
		$this->db->where('hippa_id', $id);
		$this->db->update('hippa', $hippa_data);
	}
	
	function deleteHippa($id)
	{
		$this->db->where('hippa_id', $id);
		$this->db->delete('hippa');
	}
	
	function add_mtm($mtm_data)
	{
		if ($this->db->insert('mtm', $mtm_data)) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	function update_mtm($id, $mtm_data)
	{
		$this->db->where('mtm_id', $id);
		$this->db->update('mtm', $mtm_data);
	}
	
	function delete_mtm($id)
	{
		$this->db->where('mtm_id', $id);
		$this->db->delete('mtm');
	}
}

/* End of file chart_model.php */
/* Location: ./system/application/models/chart_model.php */
