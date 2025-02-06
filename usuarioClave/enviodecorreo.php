<?php
include '../conexion.php';
require 'vendor/autoload.php'; // PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function generarClave() {
    return rand(100000, 999999);
}

function generarCorreo($nombre, $clave) {
    return "
    <html>
    <body>
        <p>Hola <strong>$nombre</strong>,</p>
        <p>Gracias por registrarte. Utiliza este código de validación para activar tu cuenta:</p>
        <h2>$clave</h2>
        <p>Si no realizaste este registro, puedes ignorar este mensaje.</p>
        <br>
        <p>Saludos,<br>El equipo de soporte.</p>
    </body>
    </html>";
}

function enviarCorreoValidacion($correo, $nombre, $clave) {
    $email = new PHPMailer();
    try {
        $email->isSMTP();
        $email->Host = 'smtp.gmail.com';
        $email->SMTPAuth = true;
        $email->Username = 'oivanaut125@gmail.com'; // Reemplaza con tu correo
        $email->Password = 'obhu tayh mrup jkly'; // Reemplaza con tu contraseña de aplicación
        $email->SMTPSecure = 'tls';
        $email->Port = 587;

        $email->setFrom('noreply@landing.com', 'Soporte');
        $email->addAddress($correo);
        $email->Subject = "Clave de validación de usuario";
        $email->Body = generarCorreo($nombre, $clave);
        $email->isHTML(true);

        return $email->send();
    } catch (Exception $e) {
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre_usuario'];
    $correo = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // 🔒 Encriptar contraseña
    $clave = generarClave();

    // Insertar usuario en la base de datos con la contraseña encriptada
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre_usuario, email, password, clave_asociada, estado) VALUES (?, ?, ?, ?, 0)");
    $stmt->bind_param("ssss", $nombre, $correo, $password, $clave);

    if ($stmt->execute()) {
        if (enviarCorreoValidacion($correo, $nombre, $clave)) {
            echo "Registro exitoso. Se ha enviado un código de validación a tu correo.";
        } else {
            echo "Error al enviar el correo de validación.";
        }
    } else {
        echo "Error al registrar el usuario.";
    }
    $stmt->close();
}
?>

