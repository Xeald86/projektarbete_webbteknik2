<?php

namespace Ajax\Controller;

use Exception;

require_once("View/AjaxView.php");
require_once("View/BuyView.php");
require_once("View/SellView.php");
require_once("View/ErrorView.php");
require_once("View/OverviewView.php");
require_once("View/SettingsView.php");
require_once("Model/ServiceModel.php");

class AjaxController {
	
	private $sectionView;
	private $ajaxView;
	private $service;
	
	public function __construct() {
		$this->service = new \Ajax\Model\ServiceModel();
		$this->ajaxView = new \Ajax\View\AjaxView();
		
		//Get section from querystring. throw error if missing
		try {
			$section = $this->ajaxView->getSection();
			$action = $this->ajaxView->getAction();
		}
		catch (Exception $e) {
			$section = null;
			$this->ajaxView->noSectionSent(); 
		}
		
		
		//Store choosen section and run correct section
		switch($section)
		{
		case 'buy':
			$this->buySection($action);
		  	break;
		case 'sell':
			$this->sellSection($action);
		  	break;
		case 'overview':
			$this->overviewSection($action);
		  	break;
		case 'settings':
			$this->settingsSection($action);
			break;
		default:
			$this->sectionView = new \Ajax\View\ErrorView();
		}
		
	}
	
	
	/***************** OVERVIEW SECTION ***********************
	 * @var obj of $kapitonObject
	 **********************************************************/
	private function overviewSection($action) {
		$this->sectionView = new \Ajax\View\OverviewView();
		
		//Get kaptionData from Kapiton API
		try {
			$kapitonDataObject = $this->service->getKapitonData();
		} catch (Exception $e) {
			$kapitonDataObject = null;
			$this->ajaxView->kapitonWebserviceError(); 
		}
		
		//Set dataObject to sectionView
		$this->sectionView->setKapitonDataObject($kapitonDataObject);
		
		/************** Personal account data ************/
		if($this->ajaxView->apiKeyIsSet() && $this->service->validateApiKey($this->ajaxView->getApiKey())) {
			try {
				$userDataObject = $this->service->getUserData($this->ajaxView->getApiKey());
				
				//Set UserDataObject to sectionView
				$this->sectionView->setUserDataObject($userDataObject);
			} catch (Exception $e) {
				$userDataObject = null;
				$this->ajaxView->kapitonWebserviceError(); 
			}
		} else {
			$this->sectionView->noValidApiKey();
		}
		
	}
	
	
	/******************** BUY SECTION *************************
	 *
	 **********************************************************/
	private function buySection($action) {
		if($this->ajaxView->apiKeyIsSet() && $this->ajaxView->apiKeyIsValid()) {
			$this->sectionView = new \Ajax\View\BuyView();
			
			//If setting new order
			if($action == 'save') {
				try {
					$bc = $this->sectionView->getBcAmount();
					$sek = $this->sectionView->getSekAmount();
					$api = $this->sectionView->getApiKey();
					$cell = $this->sectionView->getCellNr();
					$orderSet = $this->service->setBuyOrder($api, $sek, $bc);
				} catch (Exception $e) {
					$orderSet = false;
				}
				
				if($orderSet) {
					try {
						$this->service->casheOrder('buy', $api, $sek, $bc, $cell);
						
						if($cell == null) {
							$this->sectionView->orderWithNoCell();
						}
						else {
							$this->sectionView->orderWithCell();
						}
					} catch (Exception $e) {
						$this->sectionView->orderWithNoCashe();
					}
					
				} else {
					$this->sectionView->orderNotSet();
				}
				
			}
			
		}
		else {
			$this->ajaxView->noValidApiKey();
		}
	}
	
	
	/******************** SELL SECTION ************************
	 *
	 **********************************************************/
	private function sellSection($action) {
		if($this->ajaxView->apiKeyIsSet() && $this->ajaxView->apiKeyIsValid()) {
			$this->sectionView = new \Ajax\View\SellView();
			
			//If setting new order
			if($action == 'save') {
				try {
					$bc = $this->sectionView->getBcAmount();
					$sek = $this->sectionView->getSekAmount();
					$api = $this->sectionView->getApiKey();
					$cell = $this->sectionView->getCellNr();
					$orderSet = $this->service->setSellOrder($api, $sek, $bc);
				} catch (Exception $e) {
					$orderSet = false;
				}
				
				if($orderSet) {
					try {
						$this->service->casheOrder('sell', $api, $sek, $bc, $cell);
						
						if($cell == null) {
							$this->sectionView->orderWithNoCell();
						}
						else {
							$this->sectionView->orderWithCell();
						}
					} catch (Exception $e) {
						$this->sectionView->orderWithNoCashe();
					}
					
				} else {
					$this->sectionView->orderNotSet();
				}
				
			}
			
		}
		else {
			$this->ajaxView->noValidApiKey();
		}
	}
	
	
	/****************** SETTINGS SECTION **********************
	 *
	 **********************************************************/
	private function settingsSection($action) {
		$this->sectionView = new \Ajax\View\SettingsView();
		
		if($action == 'save') {
			//Catches sent api-code
			try {
				$apiCode = $this->sectionView->getSentApiCode();
				$cellNr = $this->sectionView->getSentCellNr();
			} catch (Exception $e) {
				$this->sectionView->noApiCodeSent(); 
			}
			
			//Reset for indicator
			$this->ajaxView->setApiKeyAsValid(false);
			
			//Validates api-code
			if($this->service->validateApiKey($apiCode)) {
				$this->sectionView->setApiCode($apiCode);
				$this->ajaxView->setApiKeyAsValid(true);
			} else {
				$this->ajaxView->setApiKeyAsValid(false);
				$this->sectionView->apiCodeInvalid(); 
			}
			
			//Validate cellNr
			if($cellNr != null) {
				if($this->sectionView->validCellNr($cellNr)) {
					$this->sectionView->setCellNr($cellNr);
				} else {
					$this->sectionView->cellNrInvalid(); 
				}
			}
			else {
				$this->sectionView->setCellNr(null);
				$this->sectionView->noCellNrGiven(); 
			}
			
		}
	}
	
	/**
	 * @return string of html from view
	 */
	public function getHtml() {
		if($this->ajaxView->errorAccurred())
			return $this->ajaxView->getErrorMsg();
		return $this->sectionView->getContentHtml();
	}
}
