<?php

require_once("Controller/AjaxController.php");

session_start();

$ctrl = new \Ajax\Controller\AjaxController();

/* Get html to send */
echo $ctrl->getHtml();