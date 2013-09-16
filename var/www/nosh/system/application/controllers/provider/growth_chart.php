<?php

class Growth_chart extends Application {
	
	function Growth_chart()
	{
		parent::Application();
		$this->load->database();
		$this->load->library('session');
		$this->load->model('encounters_model');
		$this->load->model('demographics_model');
		$this->load->model('practiceinfo_model');
		$this->load->model('chart_model');
	}
	
	function index($style)
	{
		$pid = $this->session->userdata('pid');
		$displayname = $this->session->userdata('displayname');
		$demographics = $this->demographics_model->get($pid)->row();
		$gender = $this->session->userdata('gender');
		$time = time();
		$dob = human_to_unix($demographics->DOB);
		$pedsage = ($time - $dob);
		$format = 'DATE_RFC822';
		$datenow = standard_date($format, $time);
		$datestring = "%Y-%m-%d";
		$date = mdate($datestring, $time);
		$data = array();
		$data['patientname'] = $demographics->firstname . ' ' . $demographics->lastname;
		if ($style == 'bmi-age') {
			$data['patient'] = $this->encounters_model->getBMIChart($pid);
			$data['yaxis'] = 'kg/m2';
			if ($gender == 'male') {
				$sex = 'm';
			} else {
				$sex = 'f';
			}
			$array = $this->encounters_model->getSpline($style, $sex);
			usort($array, array("growth_chart", "cmp"));
			foreach ($array as $row) {
				$data['categories'][] = (float) $row['Age'];
				$data['P5'][] = (float) $row['P5'];
				$data['P10'][] = (float) $row['P10'];
				$data['P25'][] = (float) $row['P25'];
				$data['P50'][] = (float) $row['P50'];
				$data['P75'][] = (float) $row['P75'];
				$data['P90'][] = (float) $row['P90'];
				$data['P95'][] = (float) $row['P95'];
			}
			$data['xaxis'] = 'Age (days)';
			$data['title'] = 'BMI-for-age percentiles for ' . $demographics->firstname . ' ' . $demographics->lastname . ' as of ' . $datenow;
			$val = end($data['patient']);
			$age = round($val[0]);
			$x = $val[1];
			$lms = $this->encounters_model->getLMS($style, $sex, $age);
			$l = $lms['L'];
			$m = $lms['M'];
			$s = $lms['S'];
			$val1 = $x / $m;
			if ($lms['L'] != '0') {
				$val2 = pow($val1, $l);
				$val2 = $val2 - 1;
				$val3 = $l * $s;
				$zscore = $val2 / $val3;
			} else {
				$val4 = log($val1);
				$zscore = $val4 / $s;
			}
			$percentile = $this->cdf($zscore) * 100;
			$percentile = round($percentile);
			$data['percentile'] = strval($percentile);
			echo json_encode($data);
			exit (0);
		}
		if ($style == 'weight-age') {
			$data['patient'] = $this->encounters_model->getWeightChart($pid);
			$data['yaxis'] = 'kg';
			if ($gender == 'male') {
				$sex = 'm';
			} else {
				$sex = 'f';
			}
			$array = $this->encounters_model->getSpline($style, $sex);
			usort($array, array("growth_chart", "cmp"));
			foreach ($array as $row) {
				$data['categories'][] = (float) $row['Age'];
				$data['P5'][] = (float) $row['P5'];
				$data['P10'][] = (float) $row['P10'];
				$data['P25'][] = (float) $row['P25'];
				$data['P50'][] = (float) $row['P50'];
				$data['P75'][] = (float) $row['P75'];
				$data['P90'][] = (float) $row['P90'];
				$data['P95'][] = (float) $row['P95'];
			}
			$data['xaxis'] = 'Age (days)';
			$data['title'] = 'Weight-for-age percentiles for ' . $demographics->firstname . ' ' . $demographics->lastname . ' as of ' . $datenow;
			$val = end($data['patient']);
			$age = round($val[0]);
			$x = $val[1];
			$lms = $this->encounters_model->getLMS($style, $sex, $age);
			$l = $lms['L'];
			$m = $lms['M'];
			$s = $lms['S'];
			$val1 = $x / $m;
			$data['val1'] = $val1;
			if ($lms['L'] != '0') {
				$val2 = pow($val1, $l);
				$val2 = $val2 - 1;
				$val3 = $l * $s;
				$zscore = $val2 / $val3;
			} else {
				$val4 = log($val1);
				$zscore = $val4 / $s;
			}
			$percentile = $this->cdf($zscore) * 100;
			$percentile = round($percentile);
			$data['percentile'] = strval($percentile);
			echo json_encode($data);
			exit (0);
		}
		if ($style == 'height-age') {
			$data['patient'] = $this->encounters_model->getHeightChart($pid);
			$data['yaxis'] = 'cm';
			if ($gender == 'male') {
				$sex = 'm';
			} else {
				$sex = 'f';
			}
			$array = $this->encounters_model->getSpline($style, $sex);
			usort($array, array("growth_chart", "cmp"));
			foreach ($array as $row) {
				$data['categories'][] = (float) $row['Age'];
				$data['P5'][] = (float) $row['P5'];
				$data['P10'][] = (float) $row['P10'];
				$data['P25'][] = (float) $row['P25'];
				$data['P50'][] = (float) $row['P50'];
				$data['P75'][] = (float) $row['P75'];
				$data['P90'][] = (float) $row['P90'];
				$data['P95'][] = (float) $row['P95'];
			}
			$data['title'] = 'Height-for-age percentiles for ' . $demographics->firstname . ' ' . $demographics->lastname . ' as of ' . $datenow;
			$val = end($data['patient']);
			$age = round($val[0]);
			$x = $val[1];
			$lms = $this->encounters_model->getLMS($style, $sex, $age);
			$l = $lms['L'];
			$m = $lms['M'];
			$s = $lms['S'];
			$val1 = $x / $m;
			if ($lms['L'] != '0') {
				$val2 = pow($val1, $l);
				$val2 = $val2 - 1;
				$val3 = $l * $s;
				$zscore = $val2 / $val3;
			} else {
				$val4 = log($val1);
				$zscore = $val4 / $s;
			}
			$percentile = $this->cdf($zscore) * 100;
			$percentile = round($percentile);
			$data['percentile'] = strval($percentile);
			echo json_encode($data);
			exit (0);
		}
		if ($style == 'head-age') {
			$data['patient'] = $this->encounters_model->getHCChart($pid);
			$data['yaxis'] = 'cm';
			if ($gender == 'male') {
				$sex = 'm';
			} else {
				$sex = 'f';
			}
			$array = $this->encounters_model->getSpline($style, $sex);
			usort($array, array("growth_chart", "cmp"));
			foreach ($array as $row) {
				$data['categories'][] = (float) $row['Age'];
				$data['P5'][] = (float) $row['P5'];
				$data['P10'][] = (float) $row['P10'];
				$data['P25'][] = (float) $row['P25'];
				$data['P50'][] = (float) $row['P50'];
				$data['P75'][] = (float) $row['P75'];
				$data['P90'][] = (float) $row['P90'];
				$data['P95'][] = (float) $row['P95'];
			}
			$data['xaxis'] = 'Age (days)';
			$data['title'] = 'Head circumference-for-age percentiles for ' . $demographics->firstname . ' ' . $demographics->lastname . ' as of ' . $datenow;
			$val = end($data['patient']);
			$age = round($val[0]);
			$x = $val[1];
			$lms = $this->encounters_model->getLMS($style, $sex, $age);
			$l = $lms['L'];
			$m = $lms['M'];
			$s = $lms['S'];
			$val1 = $x / $m;
			if ($lms['L'] != '0') {
				$val2 = pow($val1, $l);
				$val2 = $val2 - 1;
				$val3 = $l * $s;
				$zscore = $val2 / $val3;
			} else {
				$val4 = log($val1);
				$zscore = $val4 / $s;
			}
			$percentile = $this->cdf($zscore) * 100;
			$percentile = round($percentile);
			$data['percentile'] = strval($percentile);
			echo json_encode($data);
			exit (0);
		}
		if ($style == 'weight-height') {
			$data['patient'] = $this->encounters_model->getWeightHeightChart($pid);
			$data['yaxis'] = 'kg';
			$data['xaxis'] = 'cm';
			if ($gender == 'male') {
				$sex = 'm';
			} else {
				$sex = 'f';
			}
			if ($pedsage <= 63113852) {
				$array1 = $this->encounters_model->getSpline('weight-length', $sex);
				usort($array1, array("growth_chart", "cmp1"));
				$i = 0;
				foreach ($array1 as $row1) {
					$data['P5'][$i][] = (float) $row1['Height'];
					$data['P5'][$i][] = (float) $row1['P5'];
					$data['P10'][$i][] = (float) $row1['Height'];
					$data['P10'][$i][] = (float) $row1['P10'];
					$data['P25'][$i][] = (float) $row1['Height'];
					$data['P25'][$i][] = (float) $row1['P25'];
					$data['P50'][$i][] = (float) $row1['Height'];
					$data['P50'][$i][] = (float) $row1['P50'];
					$data['P75'][$i][] = (float) $row1['Height'];
					$data['P75'][$i][] = (float) $row1['P75'];
					$data['P90'][$i][] = (float) $row1['Height'];
					$data['P90'][$i][] = (float) $row1['P90'];
					$data['P95'][$i][] = (float) $row1['Height'];
					$data['P95'][$i][] = (float) $row1['P95'];
					$i++;
				}
			} else {
				$array2 = $this->encounters_model->getSpline($style, $sex);
				usort($array2, array("growth_chart", "cmp2"));
				$j = 0;
				foreach ($array2 as $row1) {
					$data['P5'][$j][] = (float) $row1['Height'];
					$data['P5'][$j][] = (float) $row1['P5'];
					$data['P10'][$j][] = (float) $row1['Height'];
					$data['P10'][$j][] = (float) $row1['P10'];
					$data['P25'][$j][] = (float) $row1['Height'];
					$data['P25'][$j][] = (float) $row1['P25'];
					$data['P50'][$j][] = (float) $row1['Height'];
					$data['P50'][$j][] = (float) $row1['P50'];
					$data['P75'][$j][] = (float) $row1['Height'];
					$data['P75'][$j][] = (float) $row1['P75'];
					$data['P90'][$j][] = (float) $row1['Height'];
					$data['P90'][$j][] = (float) $row1['P90'];
					$data['P95'][$j][] = (float) $row1['Height'];
					$data['P95'][$j][] = (float) $row1['P95'];
					$j++;
				}
			}
			$data['title'] = 'Weight-height for ' . $demographics->firstname . ' ' . $demographics->lastname . ' as of ' . $datenow;
			$val = end($data['patient']);
			$length = round($val[0]);
			$data['length'] = $length;
			$x = $val[1];
			if ($pedsage <= 63113852) {
				$lms = $this->encounters_model->getLMS1('weight-length', $sex, $length);
			} else {
				$lms = $this->encounters_model->getLMS2($style, $sex, $length);
			}
			$l = $lms['L'];
			$m = $lms['M'];
			$s = $lms['S'];
			$val1 = $x / $m;
			if ($lms['L'] != '0') {
				$val2 = pow($val1, $l);
				$val2 = $val2 - 1;
				$val3 = $l * $s;
				$zscore = $val2 / $val3;
			} else {
				$val4 = log($val1);
				$zscore = $val4 / $s;
			}
			$percentile = $this->cdf($zscore) * 100;
			$percentile = round($percentile);
			$data['percentile'] = strval($percentile);
			echo json_encode($data);
			exit (0);
		}
	}
	
	function erf($x)
	{
		$pi = 3.1415927; 
		$a = (8*($pi - 3))/(3*$pi*(4 - $pi));
		$x2 = $x * $x;
		$ax2 = $a * $x2;
		$num = (4/$pi) + $ax2;
		$denom = 1 + $ax2;
		$inner = (-$x2)*$num/$denom;
		$erf2 = 1 - exp($inner);
		return sqrt($erf2);
	}
	
	function cdf($n)
	{
		if($n < 0) {
			return (1 - $this->erf($n / sqrt(2)))/2; 
		} else {
			return (1 + $this->erf($n / sqrt(2)))/2; 
		}
	}
	
	function cmp($a, $b) 
	{
		return $a["Age"] - $b["Age"];
	}
	
	function cmp1($a, $b) 
	{
		if ($a["Length"] == $b["Length"]) {
			return 0;
		}
		return ($a["Length"] < $b["Length"]) ? -1 : 1;
	}
	
	function cmp2($a, $b) 
	{
		if ($a["Height"] == $b["Height"]) {
			return 0;
		}
		return ($a["Height"] < $b["Height"]) ? -1 : 1;
	}
}
/* End of file: growth_chart.php */
/* Location: application/controllers/provider/growth_chart.php */
