<?php
require_once("../Atheme-XMLRPC.php");
$Atheme = new Atheme("http://127.0.0.1:8080/xmlrpc");
$authToken = $Atheme->nsIdentify("nickname", "password");
print_r($Atheme->nsSet("password", "password2")."\n");
print_r($Atheme->nsSet("password", "password")."\n");
print_r($Atheme->nsSetMeta("URL", "https://www.example.com/"));
print_r($Atheme->nsInfo("nickname"));
$Atheme->nsLogout();