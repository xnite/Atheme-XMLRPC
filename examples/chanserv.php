<?php
require_once("../Atheme-XMLRPC.php");
$Atheme = new Atheme("http://127.0.0.1:8080/xmlrpc");
$authToken = $Atheme->nickserv->identify("nickname", "password");
$Atheme->chanserv = new Chanserv($Atheme->xmlrpc_url);

$Atheme->chanserv->authToken =  $Atheme->nickserv->authToken;
$Atheme->chanserv->username = $Atheme->nickserv->username;

print_r($Atheme->chanserv->register("#test_".rand(0,99)));
print_r($Atheme->chanserv->info("#lobby"));
print_r($Atheme->chanserv->akickList("#lobby"));
$Atheme->nickserv->logout();