<?php

if((isset($_COOKIE["userlog"]) && !empty($_COOKIE["userlog"]))){
    $config["logged"] = true;
}

include("database.php");
$config["database"] = $db["default"];

?>
