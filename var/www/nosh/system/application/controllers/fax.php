<?php

class Fax extends Application
{

	function Fax()
	{
		parent::Application();
		$this->load->database();
		$this->load->helper('file');
		$this->load->helper('date');
	}

	// --------------------------------------------------------------------

	function index()
	{
		$query1 = $this->db->query("SELECT * FROM practiceinfo WHERE practice_id=1");
		foreach ($query1->result_array() as $row1) {
			$fax_type = $row1['fax_type'];
			$smtp_user = $row1['fax_email'];
			$smtp_pass = $row1['fax_email_password'];
			$smtp_host = $row1['fax_email_hostname'];
		}
		if ($fax_type != "") {
			if ($fax_type === "efaxsend.com") {
				$email_sender = "message@inbound.efax.com";
				$email_subject = 'eFax from';
			}
			if ($fax_type === "metrofax.com") {
				$email_sender = "faxbounce@fax.metrohispeed.com";
				$email_subject = 'A fax has arrived from';
			}
			if ($fax_type === "rcfax.com") {
				$email_sender = "notify@ringcentral.com";
				$email_subject = 'New Fax Message from';
			}
			$hostname = "{" . $smtp_host . "/imap/ssl/novalidate-cert}INBOX";
			$search_query = 'UNSEEN FROM "' . $email_sender . '" SUBJECT "'. $email_subject . '"';
			$connection = imap_open($hostname,$smtp_user,$smtp_pass) or die('Cannot connect to Gmail: ' . imap_last_error());
			$emails = imap_search($connection,$search_query);
			if($emails) {
				rsort($emails);
				foreach($emails as $messageNumber) {
					$structure = imap_fetchstructure($connection, $messageNumber);
					$flattenedParts = $this->flattenParts($structure->parts);
					$info = imap_headerinfo($connection, $messageNumber);
					$date = strtotime($info->date);
					$data['fileDateTime'] = date('Y-m-d H:i:s', $date);
					foreach($flattenedParts as $partNumber => $part) {
						if ($part->type === 0) {
							if ($fax_type === "efaxsend.com") {
								$subject = explode(" - ", $info->subject);
								$from = str_replace("eFax from ", "", $subject[0]);
								$pages = strstr($subject[1], ' ', true);
							} else {
								if($part->subtype === "PLAIN") {
									$message = $this->getPart($connection, $messageNumber, $partNumber, $part->encoding);
									$from_pos_s = strpos($message, "From:");
									$from_substr = substr($message, $from_pos_s);
									if ($fax_type === "metrofax.com") {
										$from_substr1 = strstr($from_substr, 'To:', true);
									} elseif ($fax_type === "rcfax.com") {
										$from_substr1 = strstr($from_substr, 'Received:', true);
									} else {
										$from_substr1 = strstr($from_substr, '=', true);
									}
									$from = strstr($from_substr1, ':');
									$from = str_replace(": ", "", $from);
									if ($fax_type === "rcfax.com") {
										$pages_pos_s = strpos($message, "Pages:");
										$pages_substr = substr($message, $pages_pos_s);
										$pages_substr1 = strstr($pages_substr, 'To:', true);
									} else {
										$pages_pos_s = strpos($message, "Page");
										$pages_substr = substr($message, $pages_pos_s);
										$pages_substr1 = strstr($pages_substr, '=', true);
									}
									$pages = strstr($pages_substr1, ':');
									$pages = str_replace(": ", "", $pages);
								}
							}
							$data['fileFrom'] = $from;
							$data['filePages'] = $pages;
						}
						$filename = $this->getFilenameFromPart($part);
						if($filename) {
							// it's an attachment
							$attachment = $this->getPart($connection, $messageNumber, $partNumber, $part->encoding);
							// save attachment
							$rp = '_' . time() . '.pdf';
							$file1 = str_replace('.pdf', $rp, $filename);
							$file2 = str_replace('.PDF', '', $filename);
							$path = '/var/www/nosh/received/' . $file1;
							$xfp = fopen($path, 'w');
							if( $xfp ) {
								fwrite($xfp, $attachment);
								fclose($xfp);
							} else {
								die('Error saving attachment!');
							}
							// create thumbnail
							$path2 =  '/var/www/nosh/received/thumbnails/' . $file2 . '.png';
							$com3 = "convert \"{$path}[0]\" -colorspace RGB -geometry 300 \"$path2\"";
							exec($com3);
							$data['fileName'] = $file1;
							$data['filePath'] = $path;
						}
					}
					$this->db->insert('received', $data);
				}
			}
		}
		exit (0);
	}
	
	function flattenParts($messageParts, $flattenedParts = array(), $prefix = '', $index = 1, $fullPrefix = true)
	{
		foreach($messageParts as $part) {
			$flattenedParts[$prefix.$index] = $part;
			if(isset($part->parts)) {
				if($part->type == 2) {
					$flattenedParts = $this->flattenParts($part->parts, $flattenedParts, $prefix.$index.'.', 0, false);
				}
				elseif($fullPrefix) {
					$flattenedParts = $this->flattenParts($part->parts, $flattenedParts, $prefix.$index.'.');
				}
				else {
					$flattenedParts = $this->flattenParts($part->parts, $flattenedParts, $prefix);
				}
				unset($flattenedParts[$prefix.$index]->parts);
			}
			$index++;
		}
		return $flattenedParts;
	}
	
	function getPart($connection, $messageNumber, $partNumber, $encoding)
	{
		$data = imap_fetchbody($connection, $messageNumber, $partNumber);
		switch($encoding) {
			case 0: return $data; // 7BIT
			case 1: return $data; // 8BIT
			case 2: return $data; // BINARY
			case 3: return base64_decode($data); // BASE64
			case 4: return quoted_printable_decode($data); // QUOTED_PRINTABLE
			case 5: return $data; // OTHER
		}
	}
	
	function getFilenameFromPart($part)
	{
		$filename = '';
		if($part->ifdparameters) {
			foreach($part->dparameters as $object) {
				if(strtolower($object->attribute) == 'filename') {
					$filename = $object->value;
				}
			}
		}
		if(!$filename && $part->ifparameters) {
			foreach($part->parameters as $object) {
				if(strtolower($object->attribute) == 'name') {
					$filename = $object->value;
				}
			}
		}
		return $filename;
	}
}
/* End of file: fax.php */
/* Location: application/controllers/fax.php */
