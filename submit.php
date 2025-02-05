<?php 
Include 'conexion.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $correo = $conn->real_escape_string($_POST['correo']);
    $mensaje = $conn->real_escape_string($_POST['mensaje']);


    $sql = "INSERT INTO contactos (nombre, correo, mensaje) VALUES ('$nombre', '$correo', '$mensaje');";

    if($conn->query($sql) === TRUE) {
        header("Location: success.php?status=success");
        exit();
    } else {
        header("Location: success.php?status=error");
        exit();
    }
}

$conn->close();

?>