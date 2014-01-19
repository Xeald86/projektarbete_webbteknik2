<?php


namespace Ajax\View;

class OverviewView {
	
	private $kapitonDataObject;
	private $userDataObject;
	private $validApi = true;
	
	public function setKapitonDataObject($obj) {
		$this->kapitonDataObject = $obj;
	}
	
	public function setUserDataObject($obj) {
		$this->userDataObject = $obj;
	}
	
	public function getContentHtml() {
		assert(isset($this->kapitonDataObject));
		
		$ask = $this->kapitonDataObject['ask'];
		$bid = $this->kapitonDataObject['bid'];
		$hi = $this->kapitonDataObject['hi'];
		$low = $this->kapitonDataObject['low'];
		
		$html = "
			<p>Detta är en överblick av Kapitons och dina egna tillgångar.</p>
			
			<h3>Nuvarande prisinformation</h3>
			Säljpris: $ask kr<br />
			Köppris: $bid kr<br />
			Högsta bud: $hi kr<br />
			Lägsta bud: $low kr<br />
		";
		
		//If api-key is set
		if($this->validApi) {
			$available_sek = $this->userDataObject['available_sek'];
			$available_btc = $this->userDataObject['available_btc'];
			$total_sek = $this->userDataObject['total_sek'];
			$total_btc = $this->userDataObject['total_btc'];
			$fee = $this->userDataObject['fee'];
			
			$html .= "
				<h3>Ditt konto</h3>
				Tillgängliga kronor: $available_sek kr (Totalt: $total_sek kr)<br />
				Tillgängliga bitcoins: $available_btc bc (Totalt: $total_btc bc)<br />
				Kapiton-avgift: $fee bc
				
			";
		} else {
			$html .= "
				<h3>Ditt konto</h3>
				<i>Du är inte inloggad med en giltig API-nyckel ännu.</i>
			";
		}
		
		$html .= "<p>
					<small>*All information uppdateras var 3e minut. Vid köp/sälj sker automatisk uppdatering.</small>
				</p>";
		
		return $html;
	}
	
	public function noValidApiKey() {
		$this->validApi = false;
	}
	
}