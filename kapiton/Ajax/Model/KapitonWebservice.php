<?php

namespace Ajax\Model;

use Exception;

class KapitonWebservice {

    public function __construct()
    {
        // Empty
    }
	
	/**
	 * @return obj
	 */
	public function getKapitonData() {
		$curl = curl_init();
		curl_setopt_array($curl, array(
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_FOLLOWLOCATION => 1,
		    CURLOPT_SSL_VERIFYPEER => false,
		    CURLOPT_URL => 'https://kapiton.se/api/0/ticker',
		    CURLOPT_USERAGENT => 'Kapiton Notifier Project (wester.jimmy@gmail.com)',
		    CURLOPT_HTTPHEADER, array(
			    'Content-Type: application/json',
			    'Accept: application/json'
			)
		));
		
		// Send request
		$curl_resp = curl_exec($curl);
		
		// Close request
		curl_close($curl);
		
		// Decode to object
		$json = json_decode($curl_resp, true);
		
		if($json == null)
		{
			throw new Exception("Corrupt data from Kapiton", 1);
		}
		else {
			return $json;
		}
	}
	
	
	
	/**
	 * @return obj
	 */
	public function getUserData($key) {
		$curl = curl_init();
		
		$data = array('api_key'=>$key);
		$data = http_build_query($data, '', '&amp;');
		
		curl_setopt_array($curl, array(
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_FOLLOWLOCATION => 1,
		    CURLOPT_SSL_VERIFYPEER => false,
		    CURLOPT_POST => 1,
		    CURLOPT_URL => 'https://kapiton.se/api/0/auth/getuserinfo',
		    CURLOPT_USERAGENT => 'Kapiton Notifier Project (wester.jimmy@gmail.com)',
		    //CURLOPT_HTTPHEADER, array(
			//    'Content-Type: application/json',
			//    'Accept: application/json'
			//),
			CURLOPT_POSTFIELDS => $data
		));
		
		// Send request
		$curl_resp = curl_exec($curl);
		
		// Close request
		curl_close($curl);
		
		// Decode to object
		$json = json_decode($curl_resp, true);
		
		if($json == null)
		{
			throw new Exception("Corrupt data from Kapiton", 1);
		}
		else {
			return $json;
		}
	}
	
	
	public function validateApiKey($key) {
		$curl = curl_init();
		
		$data = array('api_key'=>$key);
		$data = http_build_query($data, '', '&amp;');
		
		curl_setopt_array($curl, array(
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_FOLLOWLOCATION => 1,
		    CURLOPT_SSL_VERIFYPEER => false,
		    CURLOPT_POST => 1,
		    CURLOPT_URL => 'https://kapiton.se/api/0/auth/getuserinfo',
		    CURLOPT_USERAGENT => 'Kapiton Notifier Project (wester.jimmy@gmail.com)',
		    //CURLOPT_HTTPHEADER, array(
			//    'Content-Type: application/json',
			//    'Accept: application/json'
			//),
			CURLOPT_POSTFIELDS => $data
		));
		
		// Send request
		$curl_resp = curl_exec($curl);
		
		// Close request
		curl_close($curl);
		
		// Decode to object
		$json = json_decode($curl_resp, true);
		
		if(isset($json['available_sek']))
			return true;
		else
			return false;
	}
	
	/**
	 * Explain: This funktion is set to return true.
	 * This is becouse the API is not working and this application is
	 * for a demonstration of how it would work.
	 */
	public function setBuyOrder($key, $sek, $bc) {
		return true;
	}
	
	/**
	 * Explain: This funktion is set to return true.
	 * This is becouse the API is not working and this application is
	 * for a demonstration of how it would work.
	 */
	public function setSellOrder($key, $sek, $bc) {
		return true;
	}
}