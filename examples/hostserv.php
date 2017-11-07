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
if(is_object($Atheme->hostserv))
{
	print_r($Atheme->hostserv->request("test.user") . "\n");
	print_r($Atheme->hostserv->offerList() . "\n");
	print_r($Atheme->hostserv->take("\$account/Registered/User") . "\n");
}
$Atheme->nickserv->logout();