<?php

namespace Ajax\Model;

use Exception;

class MosmsWebservice {

    public function __construct()
    {
        // Empty
    }
	
	public function sendSmsForOrderDone($cell, $type, $bc, $sek) {
		$mosms_username = "KapNotifier";
		$mosms_password = "Kapiton123";
		$mosms_url = "http://www.mosms.com/se/sms-send.php";
		$mosms_type = "text";
		
		// Cellnumber
		$mosms_number = $cell;
		
		if($type == "buy")
			$mosms_data = "Ett köp av $bc bitcoins för $sek kronor har lyckats!";
		else
			$mosms_data = "Ett sälj av $bc bitcoins för $sek kronor har lyckats!";
		
		 
		// Call webservice
		$mosms_data = rawurlencode($mosms_data);
		 
		$result = file_get_contents($mosms_url . "?username=" . $mosms_username
			. "&password=" . $mosms_password . "&nr=" . $mosms_number . "&type="
			. $mosms_type . "&data=" . $mosms_data);
		 
		// Error?
		if ($result <> "0") {
		  throw new Exception('Error notifying customer');
		}
		
		return true;
	}
}