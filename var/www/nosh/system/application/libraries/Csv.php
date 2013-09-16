<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Csv {

    function parse($data, $delimiter = ',', $enclosure = '"', $newline = "\n"){
		$pos = $last_pos = -1;
		$end = strlen($data);
		$row = 0;
		$quote_open = false;
		$trim_quote = false;

		$return = array();
		
		// Create a continuous loop
		for ($i = -1;; ++$i){
			++$pos;
			// Get the positions
			$comma_pos = strpos($data, $delimiter, $pos);
			$quote_pos = strpos($data, $enclosure, $pos);
			$newline_pos = strpos($data, $newline, $pos);
			// Which one comes first?
			$pos = min(($comma_pos === false) ? $end : $comma_pos, ($quote_pos === false) ? $end : $quote_pos, ($newline_pos === false) ? $end : $newline_pos);
			// Cache it
            $char = (isset($data[$pos])) ? $data[$pos] : null;
            $done = ($pos == $end);
			// It it a special character?
			if ($done || $char == $delimiter || $char == $newline){
				// Ignore it as we're still in a quote
				if ($quote_open && !$done){
					continue;
				}

				$length = $pos - ++$last_pos;

				// Is the last thing a quote?
				if ($trim_quote){
					// Well then get rid of it
					--$length;
				}
				// Get all the contents of this column
				$return[$row][] = ($length > 0) ? str_replace($enclosure . $enclosure, $enclosure, substr($data, $last_pos, $length)) : '';
				// And we're done
				if ($done){
					break;
				}
				// Save the last position
				$last_pos = $pos;
				// Next row?
				if ($char == $newline){
					++$row;
				}
				$trim_quote = false;
			}
			// Our quote?
			else if ($char == $enclosure){
				// Toggle it
				if ($quote_open == false){
					// It's an opening quote
					$quote_open = true;
					$trim_quote = false;
					// Trim this opening quote?
					if ($last_pos + 1 == $pos){
						++$last_pos;
					}
				} else {
					// It's a closing quote
					$quote_open = false;
					// Trim the last quote?
					$trim_quote = true;
				}
			}
		}
		return $return;
    }
}

?>
