<?php


namespace Ajax\View;

use Exception;

class AjaxView {

	private $errorMsg;
	private static $cookieApiCodeLocation = "view::settings::apiCode";
	private static $sessionApiCodeValid = "view::settings::apiCodeValid";
	
	//Checks if apiCode is set
	public function apiKeyIsSet() {
		return (isset($_COOKIE[self::$cookieApiCodeLocation]));
	}
	
	//Get stored api-key
	public function getApiKey() {
		return (isset($_COOKIE[self::$cookieApiCodeLocation])) ? $_COOKIE[self::$cookieApiCodeLocation] : '';
	}
	
	//Set api-key as valid
	public function setApiKeyAsValid($valid) {
		$_SESSION[self::$sessionApiCodeValid] = ($valid) ? true : false;
	}
	
	//Is api-key valid
	public function apiKeyIsValid() {
		if(isset($_SESSION[self::$sessionApiCodeValid])) 
			return ($_SESSION[self::$sessionApiCodeValid] == true) ? true : false;
		else 
			return false;
	}
	
	/**
	 * @return string of querystring
	 */
	public function getSection() {
		if(isset($_GET['sec']))
			return mysql_real_escape_string($_GET['sec']);
		throw new Exception('Error getting section from querystring!');
	}
	
	/**
	 * @return string of querystring
	 */
	public function getAction() {
		if(isset($_GET['a']))
			return mysql_real_escape_string($_GET['a']);
		return null;
	}
	
	
	/************ ERROR SECTION *****************/
	/**
	 * @param string $msg
	 * Explain: sets message to $errorMsg
	 */
	private function setErrorMsg($msg) {
		if(empty($this->errorMsg))
			$this->errorMsg = $msg;
		else
			$this->errorMsg .= "<br />$msg";
	}
	
	/**
	 * @return string of $errorMsg
	 */
	public function getErrorMsg() {
		return $this->errorMsg;
	}
	
	/**
	 * Explain: checks if errorMessage is stored or not
	 * @return bool
	 */
	public function errorAccurred() {
		return (!empty($this->errorMsg));
	}
	
	public function noSectionSent() {
		$this->setErrorMsg('Ingen sektion angiven vid anrop!');
	}
	
	public function noValidApiKey() {
		$this->setErrorMsg('<strong>Denna sektion kräver att du har en godkänd api-nyckel angiven i dina inställningar!</strong><br />1. Om du har angivit en api-nyckel var vänlig kontrollera denna och försök igen.<br />2. Om ingen api-nyckel är angiven kan du göra detta under "Inställningar".');
	}
	
	public function kapitonWebserviceError() {
		$this->setErrorMsg('Fel vid hämtnign av data från Kapiton!');
	}
	
}