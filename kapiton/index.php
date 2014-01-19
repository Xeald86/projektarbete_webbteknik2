<?php

require_once("View/AppView.php");
require_once("View/HTMLPageView.php");
require_once("Controller/AppController.php");

session_start();

$view =  new \View\AppView();
$ctrl = new \Controller\AppController($view);

/* Get html to display */
$html = $ctrl->getHtml();

/* Merge html */
$page = new \View\HTMLPageView();
echo $page->getPage("Kapiton Notifier 0.1", $html);