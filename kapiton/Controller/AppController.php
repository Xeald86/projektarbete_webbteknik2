<?php

namespace Controller;

class AppController {
	
	/**
	 * @var \View\AppView $view
	 */
	private $view;
	
	/**
	 * @param \View\AppView $view
	 */
	public function __construct(\View\AppView $view) {
		$this->view = $view;
	}
	
	/**
	 * @return string of html from view
	 */
	public function getHtml() {
		return $this->view->getHtml();
	}
}
