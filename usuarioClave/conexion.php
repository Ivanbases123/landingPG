<?php
$servername = "localhost";
$username = "root";
$password = "1234";
$dataBase = "code_vault";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dataBase);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
