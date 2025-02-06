<?php
include '../conexion.php';
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function generarClave() {
    return rand(100000, 999999);
}

function enviarCorreoValidacion($correo, $clave) {
    $email = new PHPMailer();
    try {
        $email->isSMTP();
        $email->Host = 'smtp.gmail.com';
        $email->SMTPAuth = true;
        $email->Username = 'oivanaut125@gmail.com';
        $email->Password = 'obhu tayh mrup jkly';
        $email->SMTPSecure = 'tls';
        $email->Port = 587;

        $email->setFrom('noreply@landing.com', 'Soporte');
        $email->addAddress($correo);
        $email->Subject = "Nuevo código de validación";
        $email->Body = "Tu nuevo código de validación es: <strong>$clave</strong>";
        $email->isHTML(true);

        return $email->send();
    } catch (Exception $e) {
        return false;
    }
}

if (isset($_GET['email'])) {
    $correo = $_GET['email'];
    $clave_nueva = generarClave();

    // Actualizo el nuevo código en la base de datos
    $updateClave = $conn->prepare("UPDATE usuarios SET clave_asociada = ? WHERE email = ?");
    $updateClave->bind_param("is", $clave_nueva, $correo);
    $updateClave->execute();

    // Envío el nuevo código por correo
    if (enviarCorreoValidacion($correo, $clave_nueva)) {
        echo "Se ha enviado un nuevo código a tu correo.";
    } else {
        echo "Error al enviar el correo.";
    }
} else {
    echo "No se especificó un correo.";
}
?>
