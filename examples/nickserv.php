<?php
require_once("../Atheme-XMLRPC.php");
$Atheme = new Atheme("http://127.0.0.1:8080/xmlrpc");
$Auth = $Atheme->nickserv->identify("nickname", "password");
switch($Auth->getStatusCode())
{
	case 200:
		// Authentication was successful
		$token = $Auth->getReply();
		echo "Authentication succeeded!\n";
		print_r($Atheme->nickserv->set("password", "password2") . "\n");
		print_r($Atheme->nickserv->set("password", "password") . "\n");
		print_r($Atheme->nickserv->setMeta("URL", "https://www.example.com/"));
		print_r($Atheme->nickserv->info("nickname"));
		$Atheme->nickserv->logout();
		break;
	case 401:
		//Authentication failed!
	default:
		echo "Authentication failed! :(\n";
		break;
}