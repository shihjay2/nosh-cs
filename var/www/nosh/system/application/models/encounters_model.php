<?php
class Encounters_model extends Model {

	function Encounters_model()
	{
		parent::Model();
		$this->obj =& get_instance();
	}

	// --------------------------------------------------------------------

	function getNumberDrafts($displayname)
	{
		$this->db->where('encounter_provider', $displayname);
		$this->db->where('encounter_signed', 'No');
		$query = $this->db->get('encounters');
		return $query->num_rows();
	}
	
	function addEncounter($encounter_data)
	{
		if ($this->db->insert('encounters', $encounter_data)) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}

	function addHPI($hpi_data)
	{
		if ($this->db->insert('hpi', $hpi_data)) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	function addROS($ros_data)
	{
		if ($this->db->insert('ros', $ros_data)) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}

	function addOtherHistory($other_history_data)
	{
		if ($this->db->insert('other_history', $other_history_data)) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	function addVitals($vitals_data)
	{
		if ($this->db->insert('vitals', $vitals_data)) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	function addPE($pe_data)
	{
		if ($this->db->insert('pe', $pe_data)) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	function addLabs($labs_data)
	{
		if ($this->db->insert('labs', $labs_data)) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}	
	
	function addProcedure($procedure_data)
	{
		if ($this->db->insert('procedure', $procedure_data)) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	function addProcedureTemplate($data)
	{
		if ($this->db->insert('procedurelist', $data)) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}

	function addAssessment($assessment_data)
	{
		if ($this->db->insert('assessment', $assessment_data)) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	function addOrders($orders_data)
	{
		if ($this->db->insert('orders', $orders_data)) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	function addRX($rx_data)
	{
		if ($this->db->insert('rx', $rx_data)) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	function addPlan($plan_data)
	{
		if ($this->db->insert('plan', $plan_data)) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	function addImmunizations($immunizations_data)
	{
		if ($this->db->insert('immunizations', $immunizations_data)) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}

	function addRXList($rx_list_data)
	{
		if ($this->db->insert('rx_list', $rx_list_data)) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	function addBilling($billing_data)
	{
		if ($this->db->insert('billing', $billing_data)) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	function addBillingCore($billing_data)
	{
		if ($this->db->insert('billing_core', $billing_data)) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	// --------------------------------------------------------------------
	
	function getEncounter($eid)
	{
		$this->db->where('eid', $eid);
		return $this->db->get('encounters');
	}
	
	function getHPI($eid)
	{
		$this->db->where('eid', $eid);
		return $this->db->get('hpi');
	}
	
	function getROS($eid)
	{
		$this->db->where('eid', $eid);
		return $this->db->get('ros');
	}

	function getOtherHistory($eid)
	{
		$this->db->where('eid', $eid);
		return $this->db->get('other_history');
	}

	function getVitals($eid)
	{
		$this->db->where('eid', $eid);
		return $this->db->get('vitals');
	}

	function getPE($eid)
	{
		$this->db->where('eid', $eid);
		return $this->db->get('pe');
	}
	
	function getLabs($eid)
	{
		$this->db->where('eid', $eid);
		return $this->db->get('labs');
	}
	
	function getProcedure($eid)
	{
		$this->db->where('eid', $eid);
		return $this->db->get('procedure');
	}
	
	function getAssessment($eid)
	{
		$this->db->where('eid', $eid);
		return $this->db->get('assessment');
	}

	function getOrders($eid)
	{
		$this->db->where('eid', $eid);
		return $this->db->get('orders');
	}
	
	function getOrders_labs($eid)
	{
		$this->db->where('eid', $eid);
		$this->db->where('orders_labs !=', '');
		return $this->db->get('orders');
	}
	
	function getOrders_radiology($eid)
	{
		$this->db->where('eid', $eid);
		$this->db->where('orders_radiology !=', '');
		return $this->db->get('orders');
	}
	
	function getOrders_cp($eid)
	{
		$this->db->where('eid', $eid);
		$this->db->where('orders_cp !=', '');
		return $this->db->get('orders');
	}
	
	function getOrders_ref($eid)
	{
		$this->db->where('eid', $eid);
		$this->db->where('orders_referrals !=', '');
		return $this->db->get('orders');
	}
	
	function getRX($eid)
	{
		$this->db->where('eid', $eid);
		return $this->db->get('rx');
	}

	function getPlan($eid)
	{
		$this->db->where('eid', $eid);
		return $this->db->get('plan');
	}	

	function getBilling($eid)
	{
		$this->db->where('eid', $eid);
		return $this->db->get('billing');
	}
	
	function getBillingCore($eid)
	{
		$this->db->where('eid', $eid);
		return $this->db->get('billing_core');
	}
	
	// --------------------------------------------------------------------
	
	function updateEncounter($eid, $encounter_data)
	{
		$this->db->where('eid', $eid);
		$this->db->update('encounters', $encounter_data);
	}
	
	function updateHPI($eid, $hpi_data)
	{
		$this->db->where('eid', $eid);
		$this->db->update('hpi', $hpi_data);
	}

	function updateROS($eid, $ros_data)
	{
		$this->db->where('eid', $eid);
		$this->db->update('ros', $ros_data);
	}
	
	function updateOtherHistory($eid, $other_history_data)
	{
		$this->db->where('eid', $eid);
		$this->db->update('other_history', $other_history_data);
	}

	function updateVitals($eid, $vitals_data)
	{
		$this->db->where('eid', $eid);
		$this->db->update('vitals', $vitals_data);
	}
	
	function updatePE($eid, $pe_data)
	{
		$this->db->where('eid', $eid);
		$this->db->update('pe', $pe_data);
	}
	
	function updateLabs($eid, $labs_data)
	{
		$this->db->where('eid', $eid);
		$this->db->update('labs', $labs_data);
	}
	
	function updateProcedure($eid, $procedure_data)
	{
		$this->db->where('eid', $eid);
		$this->db->update('procedure', $procedure_data);
	}
	
	function updateProcedureTemplate($id, $data)
	{
		$this->db->where('procedurelist_id', $id);
		$this->db->update('procedurelist', $data);
	}
	
	function updateAssessment($eid, $assessment_data)
	{
		$this->db->where('eid', $eid);
		$this->db->update('assessment', $assessment_data);
	}
	
	function updateOrders($eid, $orders_data)
	{
		$this->db->where('eid', $eid);
		$this->db->update('orders', $orders_data);
	}
	
	function updateRX($eid, $rx_data)
	{
		$this->db->where('eid', $eid);
		$this->db->update('rx', $rx_data);
	}
	
	function updatePlan($eid, $plan_data)
	{
		$this->db->where('eid', $eid);
		$this->db->update('plan', $plan_data);
	}

	function updateBilling($eid, $billing_data)
	{
		$this->db->where('eid', $eid);
		$this->db->update('billing', $billing_data);
	}
	
	function updateBillingCore($id, $billing_data)
	{
		$this->db->where('billing_core_id', $id);
		$this->db->update('billing_core', $billing_data);
	}
	
	// --------------------------------------------------------------------
	
	function deleteHPI($eid)
	{
		$this->db->where('eid', $eid);
		$this->db->delete('hpi');
	}

	function deleteROS($eid)
	{
		$this->db->where('eid', $eid);
		$this->db->delete('ros');
	}
	
	function deleteOtherHistory($eid)
	{
		$this->db->where('eid', $eid);
		$this->db->delete('other_history');
	}
	
	function deleteVitals($eid)
	{
		$this->db->where('eid', $eid);
		$this->db->delete('vitals');
	}
	
	function deletePE($eid)
	{
		$this->db->where('eid', $eid);
		$this->db->delete('pe');
	}
	
	function deleteLabs($eid)
	{
		$this->db->where('eid', $eid);
		$this->db->delete('labs');
	}
	
	function deleteProcedure($eid)
	{
		$this->db->where('eid', $eid);
		$this->db->delete('procedure');
	}

	function deleteAssessment($eid)
	{
		$this->db->where('eid', $eid);
		$this->db->delete('assessment');
	}

	function deleteOrders($eid)
	{
		$this->db->where('eid', $eid);
		$this->db->delete('orders');
	}

	function deleteRX($eid)
	{
		$this->db->where('eid', $eid);
		$this->db->delete('rx');
	}
	
	function deletePlan($eid)
	{
		$this->db->where('eid', $eid);
		$this->db->delete('plan');
	}
	
	function deleteBilling($eid)
	{
		$this->db->where('eid', $eid);
		$this->db->delete('billing');
	}
	
	function deleteBillingCore($eid)
	{
		$this->db->where('eid', $eid);
		$this->db->delete('billing_core');
	}
	
	function deleteBillingCore1($billing_core_id)
	{
		$this->db->where('billing_core_id', $billing_core_id);
		$this->db->delete('billing_core');
	}
	
	function deleteEncounter($eid)
	{
		$this->db->where('eid', $eid);
		$this->db->where('encounter_signed', 'No');
		if ($this->db->delete('encounters')) {
			$this->deleteHPI($eid); 
			$this->deleteROS($eid);
			$this->deleteOtherHistory($eid);
			$this->deleteVitals($eid);
			$this->deletePE($eid);
			$this->deleteLabs($eid);
			$this->deleteProcedure($eid);
			$this->deleteAssessment($eid);
			$this->deleteOrders($eid);
			$this->deleteRX($eid);
			$this->deletePlan($eid);
			$this->deleteBilling($eid);
			return "";
		} else {
			return "Unable to delete a signed encounter!";
		}
	}

	// --------------------------------------------------------------------

	function getWeightChart($pid)
	{
		$this->db->select('weight, pedsage');
		$this->db->where('pid', $pid);
		$this->db->where('weight !=', '');
		$this->db->order_by('pedsage', 'ASC');
		$query = $this->db->get('vitals');
		if ($query->num_rows() > 0) {
			$vals = array();
			$i = 0;
			foreach ($query->result_array() as $row) {
				$this->db->select('weight_unit');
				$query1 = $this->db->get('practiceinfo');
				$row1 = $query1->row_array();
				if ($row1['weight_unit'] == 'lbs') {
					$y = $row['weight'] / 2.20462262185;
				} else {
					$y = $row['weight'] * 1;
				}
				$x = $row['pedsage'] * 2629743 / 86400;
				$vals[$i][] = $x;
				$vals[$i][] = $y;
				$i++;
			}
			return $vals;
		} else {
			return FALSE;
		}
	}

	function getHeightChart($pid)
	{
		$this->db->select('height, pedsage');
		$this->db->where('pid', $pid);
		$this->db->where('height !=', '');
		$this->db->order_by('pedsage', 'ASC');
		$query = $this->db->get('vitals');
		if ($query->num_rows() > 0) {
			$vals = array();
			$i = 0;
			foreach ($query->result_array() as $row) {
				$this->db->select('height_unit');
				$query1 = $this->db->get('practiceinfo');
				$row1 = $query1->row_array();
				if ($row1['height_unit'] == 'in') {
					$y = $row['height'] * 2.54;
				} else {
					$y = $row['height'] * 1;
				}
				$x = $row['pedsage'] * 2629743 / 86400;
				$vals[$i][] = $x;
				$vals[$i][] = $y;
				$i++;
			}
			return $vals;
		} else {
			return FALSE;
		}		
	}
	
	function getHCChart($pid)
	{
		$this->db->select('headcircumference, pedsage');
		$this->db->where('pid', $pid);
		$this->db->where('headcircumference !=', '');
		$this->db->order_by('pedsage', 'ASC');
		$query = $this->db->get('vitals');
		if ($query->num_rows() > 0) {
			$vals = array();
			$i = 0;
			foreach ($query->result_array() as $row) {
				$this->db->select('hc_unit');
				$query1 = $this->db->get('practiceinfo');
				$row1 = $query1->row_array();
				if ($row1['hc_unit'] == 'in') {
					$y = $row['headcircumference'] * 2.54;
				} else {
					$y = $row['headcircumference'] * 1;
				}
				$x = $row['pedsage'] * 2629743 / 86400;
				$vals[$i][] = $x;
				$vals[$i][] = $y;
				$i++;
			}
			return $vals;
		} else {
			return FALSE;
		}		
	}
	
	function getBMIChart($pid)
	{
		$this->db->select('BMI, pedsage');
		$this->db->where('pid', $pid);
		$this->db->where('BMI !=', '');
		$this->db->order_by('pedsage', 'ASC');
		$query = $this->db->get('vitals');
		if ($query->num_rows() > 0) {
			$vals = array();
			$i = 0;
			foreach ($query->result_array() as $row) {
				$x = $row['pedsage'] * 2629743 / 86400;
				$vals[$i][] = $x;
				$vals[$i][] = (float) $row['BMI'];
				$i++;
			}
			return $vals;
		} else {
			return FALSE;
		}	
	}
	
	function getWeightHeightChart($pid)
	{
		$this->db->select('weight, height, pedsage');
		$this->db->where('pid', $pid);
		$this->db->where('weight !=', '');
		$this->db->where('height !=', '');
		$this->db->order_by('pedsage', 'ASC');
		$query = $this->db->get('vitals');
		if ($query->num_rows() > 0) {
			$vals = array();
			$i = 0;
			foreach ($query->result_array() as $row) {
				$this->db->select('weight_unit, height_unit');
				$query1 = $this->db->get('practiceinfo');
				$row1 = $query1->row_array();
				if ($row1['weight_unit'] == 'lbs') {
					$y = $row['weight'] / 2.20462262185;
				} else {
					$y = $row['weight'] * 1;
				}
				if ($row1['height_unit'] == 'in') {
					$x = $row['height'] * 2.54;
				} else {
					$x = $row['height'] * 1;
				}
				$vals[$i][] = $x;
				$vals[$i][] = $y;
				$i++;
			}
			return $vals;
		} else {
			return FALSE;
		}
	}
	
	function getSpline($style, $sex)
	{
		$this->db->where('type', $style);
		$this->db->where('sex', $sex);
		$query = $this->db->get('gc')->result_array();
		return $query;
	}
	
	function getLMS($style, $sex, $age)
	{
		$this->db->where('type', $style);
		$this->db->where('sex', $sex);
		$this->db->where('Age', $age);
		$query = $this->db->get('gc')->row_array();
		return $query;
	}
	
	function getLMS1($style, $sex, $length)
	{
		$this->db->where('type', $style);
		$this->db->where('sex', $sex);
		$this->db->where('Length', $length);
		$query = $this->db->get('gc')->row_array();
		return $query;
	}
	
	function getLMS2($style, $sex, $height)
	{
		$this->db->where('type', $style);
		$this->db->where('sex', $sex);
		$this->db->where('Height', $height);
		$query = $this->db->get('gc')->row_array();
		return $query;
	}
	
	// --------------------------------------------------------------------
	
	function lastEncounterNumber($pid)
	{
		$this->db->where('pid', $pid);
		$this->db->where('eid != ""');
		$this->db->orderby("eid", "desc"); 
		$this->db->limit(1);
		$query = $this->db->get('encounters');
		if ($query->num_rows() > 0) {
			return $query->row()->eid;
		} else {
			return '0';
		}
	}

	// --------------------------------------------------------------------
	
	function lastEncounterVisitDate($pid)
	{
		$this->db->where('pid', $pid);
		$this->db->where('eid != ""');
		$this->db->orderby("eid", "desc"); 
		$this->db->limit(1);
		$query = $this->db->get('encounters');
		if ($query->num_rows() > 0) {
			$result = $query->row()->encounter_DOS;
			$result1 = strtotime($result);
			return date('F jS, Y', $result1);
		} else {
			return 'No previous visits.';
		}
	}

	// --------------------------------------------------------------------
	
	function uniqueEncounter($eid)
	{
		$this->db->where('eid', $eid);
		$query = $this->db->get('encounters');
		$num_rows = $query->num_rows();
		if ($num_rows == 0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
/* End of file encounters_model.php */
/* Location: ./system/application/models/encounters_model.php */
