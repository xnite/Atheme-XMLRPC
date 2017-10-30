# NOTICE
**This is not a full implementation of the Atheme XMLRPC.** Pull Requests are welcome. :)

# Getting Started
```PHP
<?php
    // This is just an example of using Nickserv over XMLRPC
    require_once("Atheme-XMLRPC.php");
    $Atheme = new Atheme("http://127.0.0.1:8080/xmlrpc");
    $authToken = $Atheme->nickserv->Identify("nickname", "password");
    print_r($Atheme->nickserv->Set("password", "newpassword")."\n");
    print_r($Atheme->nickserv->Set("password", "password")."\n");
    print_r($Atheme->nickserv->SetMeta("URL", "https://www.example.com/"));
    print_r($Atheme->nickserv->Info("nickname"));
    $Atheme->nickserv->Logout();
?>
```