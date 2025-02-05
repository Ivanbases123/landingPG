<?php
include 'conexion.php';
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
        <p>Saludos,<br>El equipo de soporte.</p>
    </body>
    </html>";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre_usuario'];
    $correo = $_POST['email'];
    $clave = generarClave();

    // Guardar usuario en la base de datos
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, correo, clave_validacion, estado) VALUES (?, ?, ?, 0)");
    $stmt->bind_param("sss", $nombre, $correo, $clave);

    if ($stmt->execute()) {
        $email = new PHPMailer();
        $email->isSMTP();
        $email->Host = 'smtp.tudominio.com'; // Configura tu servidor SMTP
        $email->SMTPAuth = true;
        $email->Username = 'oivanaut125@gmail.com';
        $email->Password = 'obhu tayh mrup jkly';
        $email->SMTPSecure = 'tls';
        $email->Port = 587;

        $email->setFrom('noreply@landing.com', 'Soporte');
        $email->addAddress($correo);
        $email->Subject = "Clave de validación de usuario";
        $email->isHTML(true);
        $email->Body = generarCorreo($nombre, $clave);

        if ($email->send()) {
            echo "Correo enviado con éxito. Revisa tu bandeja de entrada e introduce la clave.";
        } else {
            echo "Error al enviar el correo: " . $email->ErrorInfo;
        }
    } else {
        echo "Error al registrar el usuario.";
    }
}
?>

    