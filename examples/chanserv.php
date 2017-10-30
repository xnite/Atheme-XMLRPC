<?php
require_once("../Atheme-XMLRPC.php");
$Atheme = new Atheme("http://127.0.0.1:8080/xmlrpc");
$authToken = $Atheme->nsIdentify("nickname", "password");
print_r($Atheme->csRegister("#test"));
print_r($Atheme->csInfo("#test"));
print_r($Atheme->csAkickList("#test"));
$Atheme->nsLogout();