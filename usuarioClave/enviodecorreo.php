<?php
include '../conexion.php';
require './vendor/autoload.php'; // PHPMailer

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
    $email = new PHPMailer(true); // Habilitamos excepciones
    try {
        $email->isSMTP();
        $email->Host = 'smtp.gmail.com';
        $email->SMTPAuth = true;
        $email->Username = 'oivanaut125@gmail.com'; // Reemplázalo por tu correo
        $email->Password = 'smjf wyla oyyd iyuw'; // Reemplázalo por tu contraseña de aplicación
        $email->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Seguridad TLS
        $email->Port = 587; // Puerto correcto para TLS

        $email->setFrom('oivanaut125@gmail.com', 'Soporte'); // Debe ser tu mismo correo
        $email->addAddress($correo);
        $email->Subject = "Clave de validación de usuario";
        $email->Body = generarCorreo($nombre, $clave);
        $email->isHTML(true);

        if ($email->send()) {
            return true;
        } else {
            throw new Exception("Error desconocido al enviar el correo.");
        }
    } catch (Exception $e) {
        echo "Error al enviar el correo: " . $email->ErrorInfo; // 🔴 MOSTRAR ERROR EXACTO
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
            header("Location: validacion.php");
        } else {
            echo "Error al enviar el correo de validación.";
        }
    } else {
        echo "Error al registrar el usuario.";
    }
    $stmt->close();
}
?>

