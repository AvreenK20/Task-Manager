<?php

$hostName = "localhost";
$dbUser = "testuser";
$dbPassword = "password";
$dbName = "taskmanagerdb";

$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);
if(!$conn) {
    die("Something went wrong while attempting to connect to the database");
}

?>