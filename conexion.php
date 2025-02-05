<?php
$servername = "localhost";
$username = "root";
$password = "1234";
$dataBase = "code_vault";

// Create connection
$conn = new mysqli($servername, $username, $password, $dataBase);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

?>