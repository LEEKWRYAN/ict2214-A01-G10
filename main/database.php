<?php

$hostName = "localhost";
$dbUser = "websec-admin";
$dbPassword = "!WebSec123";
$dbName = "websec";

$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);

if(!$conn) 
{
    die("Something went wrong");
}

?>
