<?php
require_once("../Atheme-XMLRPC.php");
$Atheme = new Atheme("http://127.0.0.1:8080/xmlrpc");
$Auth = $Atheme->auth("nickname", "password");
switch($Auth->getStatusCode())
{
	case 0:
		// Authentication was successful
		$token = $Auth->getReply();
		echo "Authentication succeeded!\n";
		break;
	default:
		echo "Authentication failed! :(\n";
		break;
}

if(is_object($Atheme->chanserv))
{
	print_r($Atheme->chanserv->register("#test_".rand(0,99)));
	print_r($Atheme->chanserv->info("#lobby"));
	print_r($Atheme->chanserv->akickList("#lobby"));
}
$Atheme->nickserv->logout();