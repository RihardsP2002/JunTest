<?php

$servername = "localhost";
$username = "phpmyadmin";
$password = "P@ssw0rd";
$dbname = "JunTest";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
