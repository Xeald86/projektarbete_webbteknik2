<?php

namespace View;

class HTMLPageView {
	/**
	 * @param string $title
	 * @param string $body
	 * @return string HTML
	 */
	public function getPage($title, $body) {
		return "
		<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'> 
		<html xmlns='http://www.w3.org/1999/xhtml' manifest='http://127.0.0.1/Kapiton/manifest.mf'> 
		  <head> 
		     <title>".$title."</title> 
		     <meta http-equiv='content-type' content='text/html; charset=utf-8' />
		     <link href='Css/Stilmall.css' rel='stylesheet' type='text/css' />
		     <script type='text/javascript' src='Script/jquery-1.10.2.min.js'></script>
		     <script type='text/javascript' src='Script/script.min.js'></script>
		  </head> 
		  <body>
		  	<div id='appWrapper'>
		  		<div id='appHeader'>
		  		<img src='Design/logo.png' id='logo' />
		  			<span id='netStatus' class='netOnline'>Online</span>
		  		</div>
		  		<div id='appContainer'>
		  			<div id='mainMenuWrapper'>
		  				<div id='overview' class='menuOpt selectedOpt'>Överblick</div>
		  				<div id='buy' class='menuOpt'>Köp</div>
		  				<div id='sell' class='menuOpt'>Sälj</div>
		  				<div id='settings' class='menuOpt'>Inställningar</div>
		  				<div class='clear' />
		  			</div>
		  			<div id='content'>
		  				" . $body . "
		  			</div>
		  		</div>
		  	</div>
		  </body>
		</html>
		";
	}
	

		

}
