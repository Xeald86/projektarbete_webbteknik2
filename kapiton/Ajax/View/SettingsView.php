<?php


namespace Ajax\View;

use Exception;

class SettingsView {
	
	//ApiCode Location i Cookies
	private static $cookieApiCodeLocation = "view::settings::apiCode";
	private static $cookieCellNrLocation = "view::setings::cellNr";
	
	private $errorMsg;
	private $apiCode;
	private $cellNr;
	
	public function getContentHtml() {
			
		if($this->apiCodeIsSet() && !isset($this->apiCode))
			$this->apiCode = $this->getApiCode();
		else if($this->apiCodeIsSet() == false && !isset($this->apiCode))
			$this->apiCode = '';
		
		if($this->cellNrIsSet() && !isset($this->cellNr))
			$this->cellNr = $this->getCellNr();
		else if($this->cellNrIsSet() == false && !isset($this->cellNr))
			$this->cellNr = '';
		
		
		$html = "";
		
		if($this->saveSuccess())
			$html .= "<span class='okMessage'>Inställningar sparade!</span>";
		
		if($this->errorAccurred()) {
			$error = $this->getErrorMsg();
			$html .= "<span class='failMessage'>Följande fel har inträffat:<br />$error</span>";
		}
		
		$html .= "<fieldset>
			    <legend><strong>Kontoinställningar:</strong></legend>
			    <label for='api'>API-kod</label><br />
			    <input type='password' value='$this->apiCode' class='settingsInput' id='api'><br />
			    
			    <label for='cell'>Mobilnummer</label><br />
			    <input type='text' value='$this->cellNr' class='settingsInput' id='cell'><br />
			    
			    <input id='settingsSubmit' type='button' value='Spara ändringar' />
			    <br /><small>(nyckel för test: 1837d5d94530a222935a8068db76450749ef05bdd88b19cf4ef7b189)</small>
			</fieldset>
		";
		
		return $html;
	}
	
	//Fetch apiCode
	private function getApiCode() {
		assert($_COOKIE);
		return $_COOKIE[self::$cookieApiCodeLocation];
	}
	
	//Fetch apiCode
	private function getCellNr() {
		assert($_COOKIE);
		return $_COOKIE[self::$cookieCellNrLocation];
	}
	
	//Fetch cellNr
	public function getSentCellNr() {
		if(isset($_GET['cell']))
			return mysql_real_escape_string($_GET['cell']);
		return null;
	}
	
	//Validates cellNr
	public function validCellNr($cellNr) {
		if(preg_match('/^\d{10}$/',$cellNr))
			return true;
		return false;
	}
	
	//Fetch sent apiCode
	public function getSentApiCode() {
		if(isset($_GET['api']))
			return mysql_real_escape_string($_GET['api']);
		throw new Exception('No apiCode was sent');
	}
	
	//Saves apiCode to cookie
	public function setApiCode($apiCode) {
		assert($_COOKIE);
		$cookieLastingTime = time()+3600;
		setcookie(	self::$cookieApiCodeLocation, 
					mysql_real_escape_string($apiCode), 
					$cookieLastingTime);
		$this->apiCode = $apiCode;
	}
	
	//Saves cellNr to cookie
	public function setCellNr($cellNr) {
		assert($_COOKIE);
		$cookieLastingTime = time()+3600;
		setcookie(	self::$cookieCellNrLocation, 
					mysql_real_escape_string($cellNr), 
					$cookieLastingTime);
		$this->cellNr = $cellNr;
	}
	
	//Checks if apiCode is set
	public function apiCodeIsSet() {
		return (isset($_COOKIE[self::$cookieApiCodeLocation]));
	}
	
	//Checks if apiCode is set
	public function cellNrIsSet() {
		return (isset($_COOKIE[self::$cookieCellNrLocation]));
	}
	
	//Was saved successfully?
	public function saveSuccess() {
		if(isset($_GET['a']) && $this->errorAccurred() == false)
			return ($_GET['a'] == 'save');
		return false;
	}

	
	
	
	/************ ERROR SECTION *****************/
	//Set error message
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
	
	public function noApiCodeSent() {
		$this->setErrorMsg('Ingen API-nyckel har angivits!');
	}
	
	public function apiCodeInvalid() {
		$this->setErrorMsg('Den angivna API-nyckeln är ogiltig!');
	}
	
	public function cellNrInvalid() {
		$this->setErrorMsg('Det angivna mobilnummret är ej godkänt!');
	}
	
	public function noCellNrGiven() {
		$this->setErrorMsg('Inget mobilnummer angivet! Sms-notifikation avaktiverat!');
	}
	
}