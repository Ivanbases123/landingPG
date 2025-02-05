<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo = $_POST['email'];
    $password = $_POST['password']; // En este ejemplo aún no hay contraseña en la BD

    $stmt = $conn->prepare("SELECT estado FROM usuarios WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($estado);
    $stmt->fetch();

    if ($stmt->num_rows > 0) {
        if ($estado == 1) {
            echo "Inicio de sesión exitoso.";
            // Aquí podrías redirigir a la página principal del usuario
        } else {
            echo "Tu cuenta no está activada. Verifica tu correo.";
        }
    } else {
        echo "Usuario no encontrado.";
    }
}
?>
