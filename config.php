<?php

$servername = "localhost";
$username = "id21911585_root";
$password = "P@ssw0rd";
$dbname = "id21911585_root";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
