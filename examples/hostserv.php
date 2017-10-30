<?php
require_once("../Atheme-XMLRPC.php");
$Atheme = new Atheme("http://127.0.0.1:8080/xmlrpc");
$authToken = $Atheme->nsIdentify("nickname", "password");
print_r($Atheme->hsRequest("test.user")."\n");
print_r($Atheme->hsOfferList()."\n");
print_r($Atheme->hsTake("\$account/Registered/User")."\n");
$Atheme->nsLogout();