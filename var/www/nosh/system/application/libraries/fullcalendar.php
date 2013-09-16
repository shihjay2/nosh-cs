<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

class Fullcalendar {
	
	public $js_path	 	= '';
	public $css_path 	= '';
	
	public $use_JSON	= TRUE;
	
	private $data 		= array();
	private $CI;
	
	function Fullcalendar($props = array())
	{
		if (count($props) > 0)
		{
			$this->initialize($props);
		}
		
		log_message('debug', "Fullcalendar Class Initialized");
	}
	
	function initialize($props)
	{
		if (count($props) > 0)
		{
			foreach ($props as $key => $val)
			{
				$this->$key = $val;
			}
		}

		if (empty($this->data))
			return FALSE;
	}
	
	function clear()
	{
		$this->js_path 		= '';
		$this->css_path 	= '';
		$this->use_JSON 	= TRUE;
		$this->data 		= array();
	}
	
	function set_data($data = array())
	{
		if (empty($data))
			return FALSE;
			
		$this->data = $data;
	}
	
	function __endRepeat($data)
	{
		$dayLength_ar = array('1' => 31, '2_0' => 28, '2_1' => 29, '3' => 31, '4' => 30, '5' => 31, '6' => 30, '7' => 31, '8' => 31, '9' => 30, '10' => 31, '11' => 30, '12' => 31);
		$timespan = strtotime($data['start']);
		
		switch($data['end_repeat'])
		{
			case 'endofweek':
				$day_length = (date('n',$timespan) == 2)? $dayLength_ar[date('n',$timespan).'_'.date('L',$timespan)]:$dayLength_ar[date('n',$timespan)];
				//$end_repeat = strtotime(date('Y',$timespan).'-'.date('m',$timespan).'-'.$day_length);
				switch($data['repeat'])
				{
					case 'everyday':
						$date = $data['start'];
						$end_repeat = (date('l',strtotime($date)) == 'Saturday')? strtotime(date('Y-m-d',strtotime($date))):strtotime("$date next Saturday");
						break;
					default:
						$end_repeat = strtotime(date('Y-m-d',strtotime($data['start'])));
						break;
				}
				break;
			case 'endofmonth':
				$day_length = (date('n',$timespan) == 2)? $dayLength_ar[date('n',$timespan).'_'.date('L',$timespan)]:$dayLength_ar[date('n',$timespan)];
				$end_repeat = strtotime(date('Y',$timespan).'-'.date('m',$timespan).'-'.$day_length);
				break;
			case 'endofyear':
				$end_repeat = strtotime(date('Y',strtotime($data['start'])).'-12-31');
				break;
			default:
				$end_repeat = strtotime(date('Y',strtotime($data['start'])).'-12-31');
				break;
		}
		
		return $end_repeat;
	}
	
	function generateCalendar()
	{
		$dayLength_ar = array('1' => 31, '2_0' => 28, '2_1' => 29, '3' => 31, '4' => 30, '5' => 31, '6' => 30, '7' => 31, '8' => 31, '9' => 30, '10' => 31, '11' => 30, '12' => 31);
		
		if (count($this->data) > 0)
		{
			$new_data = array();
			foreach ($this->data as $value)
			{
				if(isset($value['repeat']))
				{
					$repeat = array();
					switch($value['repeat'])
					{
						case 'everyday':
							$date = $value['start'];
							$timespan = ( ( $this->__endRepeat($value) - strtotime(date('Y-m-d',strtotime($date))) ) / 86400 );
							
							for($i = 0; $i <= $timespan; $i++)
							{
								$next = date('d M Y',strtotime("$date +{$i} day"));
								if($value['end_repeat'] == 'endofweek')
								{
									//if(	date('W',strtotime($next)) - date('W',strtotime($date)) > 0) break;
									if(	date('n',strtotime($next)) - date('n',strtotime($date)) > 0) break;
								}
								if($value['end_repeat'] == 'endofmonth')
								{
									if(	date('n',strtotime($next)) - date('n',strtotime($date)) > 0) break;
									if(	date('Y',strtotime($next)) - date('Y',strtotime($date)) > 0) break;
								}
								if($value['end_repeat'] == 'endofyear')
									if(	date('Y',strtotime($next)) - date('Y',strtotime($date)) > 0) break;
								$repeat[] = date('Y-m-d',strtotime($next));
							}
							break;
						case 'everyweek':
							$day = date('l',strtotime($value['start']));
							$date = $value['start'];
							$week = date('W',strtotime($value['start']));
							
							for($i = 0; $i <= ( date('W',$this->__endRepeat($value)) - $week ); $i++)
							{
								$next = date('d M Y',strtotime("$date +{$i} week"));
								if($value['end_repeat'] == 'endofmonth')
								{
									if(	date('n',strtotime($next)) - date('n',strtotime($date)) > 0) break;
									if(	date('Y',strtotime($next)) - date('Y',strtotime($date)) > 0) break;
								}
								if($value['end_repeat'] == 'endofyear')
									if(	date('Y',strtotime($next)) - date('Y',strtotime($date)) > 0) break;
								$repeat[] = date('Y-m-d',strtotime($next));
							}
							break;
						case 'everymonth':
							$date = $value['start'];
							$timespan = date('n', $this->__endRepeat($value)) - date('n', strtotime( date('Y-m-d',strtotime($date)) ));
							
							for($i = 0; $i <= $timespan; $i++)
							{
								$next = date('d M Y',strtotime("$date +{$i} month"));
								if(	date('Y',strtotime($next)) - date('Y',strtotime($date)) > 0) break;
								$repeat[] = date('Y-m-d',strtotime($next));
							}
							break;
					}
					
					$temp = array();
					foreach($repeat as $val)
					{
						$temp['id'] = $value['id'];
						$temp['title'] = $value['title'];
						$temp['start'] = (eregi('T',$value['start']))? $val.'T'.date('H:i',strtotime($value['start'])):$val;
						if(isset($value['end']))
							$temp['end'] = date('Y-m-d', strtotime( $val ) + ( strtotime($value['end']) - strtotime( date('Y-m-d', strtotime($value['start'])) ) ) );
						if(isset($value['url']))
							$temp['url'] = $value['url'];
							
						$new_data[] = $temp;
					}
				}
				else
					$new_data[] = $value;
			}
			
			return ($this->use_JSON)? json_encode($new_data):$new_data;
		}
		else
			return ($this->use_JSON)? json_encode(array()):array();
	}
}

/* End of file Fullcalendar.php */
/* Location: ./system/libraries/fullcalendar.php */