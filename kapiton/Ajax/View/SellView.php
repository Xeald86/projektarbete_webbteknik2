<?php


namespace Ajax\View;

class SellView {
	
	private static $cookieApiCodeLocation = "view::settings::apiCode";
	private static $cookieCellNrLocation = "view::setings::cellNr";
	private $errorMsg;
	private $msg;
	
	public function getContentHtml() {
		
		$html = "";
		
		if($this->messageIsSet())
			$html .= "<span class='okMessage'>$this->msg</span>";
		
		if($this->errorAccurred()) {
			$error = $this->getErrorMsg();
			$html .= "<span class='failMessage'>Följande fel har inträffat:<br />$error</span>";
		}
		
		$html .= "
			<fieldset>
			    <legend><strong>Registrera ny sälj-order:</strong></legend>
			    <label for='bcOrder'>Antal bitcoins</label><br />
			    <input type='text' value='0.1' class='sellOrderInput' id='bcOrder'><br />
			    <label for='sekOrder'>Önskat pris <small>(per 1st bc, ej för vald mängd)</small></label><br />
			    <input type='text' value='4900' class='sellOrderInput' id='sekOrder'><br />
			    <input id='sellOrderSubmit' type='button' value='Lägg order' />
			</fieldset>
		";
		
		return $html;
	}
	
	
	
		
	public function getBcAmount() {
		if(isset($_GET['bc']))
			return mysql_real_escape_string($_GET['bc']);
		throw new Exception('No bitcoin-amount was set');
	}
	
	public function getSekAmount() {
		if(isset($_GET['sek']))
			return mysql_real_escape_string($_GET['sek']);
		throw new Exception('No sek-amount was set');
	}
	
	public function getApiKey() {
		assert($_COOKIE);
		return $_COOKIE[self::$cookieApiCodeLocation];
	}
	
	//Fetch apiCode
	public function getCellNr() {
		if(isset($_COOKIE[self::$cookieCellNrLocation]))
			return $_COOKIE[self::$cookieCellNrLocation];
		else
			return null;
	}
	
	/************ ERROR SECTION *****************/
	//Set error message
	private function setErrorMsg($msg) {
		if(empty($this->errorMsg))
			$this->errorMsg = $msg;
		else
			$this->errorMsg .= "<br />$msg";
	}
	
	//Set message
	private function setMsg($msg) {
		if(empty($this->msg))
			$this->msg = $msg;
		else
			$this->msg .= "<br />$msg";
	}
	
	/**
	 * @return string of $errorMsg
	 */
	public function getErrorMsg() {
		return $this->errorMsg;
	}
	
	/**
	 * @return string of $msg
	 */
	public function getMsg() {
		return $this->msg;
	}
	
	/**
	 * Explain: checks if errorMessage is stored or not
	 * @return bool
	 */
	public function errorAccurred() {
		return (!empty($this->errorMsg));
	}
	
	/**
	 * @return bool
	 */
	public function messageIsSet() {
		return (!empty($this->msg));
	}
	
	public function orderNotSet() {
		$this->setErrorMsg('Order ej genomförd då Kapiton inte svarar på begäran!');
	}
	
	public function orderWithCell() {
		$this->setMsg('Order har satts och vi meddelar dig via sms när sälj genomförts!');
	}
	
	public function orderWithNoCell() {
		$this->setMsg('Order har satts! Ingen notis görs via sms för denna order!');
	}
	
	public function orderWithNoCashe() {
		$this->setMsg('Order har satts! Ingen notis görs via sms för denna order!');
	}
}