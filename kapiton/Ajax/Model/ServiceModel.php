<?php

namespace Ajax\Model;

require_once("Model/StorageModel.php");
require_once("Model/KapitonWebservice.php");
use PDO;

class ServiceModel {
	
	private $pdo;
	
	public function __construct()
    {
		$pdo = new PDO('mysql:host=localhost;dbname=kapiton', 'root', '');
		$pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, 1);
		$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$this->pdo = $pdo;
    }
	
	/**
	 * 
	 */
	public function validateApiKey($key) {
		if(isset($_SESSION['view::settings::apiCodeValid'])) {
			if($_SESSION['view::settings::apiCodeValid'] == true)
				return true;
		}
		$webservice = new \Ajax\Model\KapitonWebservice();
		return $webservice->validateApiKey($key);
	}
	 	
	/**
	 * @return obj
	 * Gets json-object from webservice and return it
	 */
	public function getKapitonData() {
		$webservice = new \Ajax\Model\KapitonWebservice();
		return $webservice->getKapitonData();
	}
	
	
	/**
	 * @return obj
	 * Gets json-object from webservice and return it
	 */
	public function getUserData($key) {
		$webservice = new \Ajax\Model\KapitonWebservice();
		return $webservice->getUserData($key);
	}
	
	/**
	 * @return bool
	 */
	public function setBuyOrder($key, $sek, $bc) {
		$webservice = new \Ajax\Model\KapitonWebservice();
		return $webservice->setBuyOrder($key, $sek, $bc);
	}
	
	/**
	 * @return bool
	 */
	public function setSellOrder($key, $sek, $bc) {
		$webservice = new \Ajax\Model\KapitonWebservice();
		return $webservice->setSellOrder($key, $sek, $bc);
	}
	

	public function casheOrder($type, $api, $sek, $bc, $cell) {
		$storage = new \Ajax\Model\StorageModel($this->pdo);
		$storage->insertOrder($type, $api, $sek, $bc, $cell);
	}
}