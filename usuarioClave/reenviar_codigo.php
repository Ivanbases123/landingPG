<?php
include '../conexion.php';
require './vendor/autoload.php';
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
        $email->Username = 'oivanaut125@gmail.com';  // Reemplaza con tu correo
        $email->Password = 'smjf wyla oyyd iyuw';  // Reemplaza con tu contraseña de aplicación
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

    // Verificar si el correo está registrado en la base de datos
    $stmt = $conn->prepare("SELECT email FROM usuarios WHERE email = ?");
    $stmt->bind_param("si", $correo);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // El correo está registrado, generamos una nueva clave
        $clave_nueva = generarClave();

        // Actualizamos el nuevo código en la base de datos
        $updateClave = $conn->prepare("UPDATE usuarios SET clave_asociada = ? WHERE email = ?");
        $updateClave->bind_param("s", $clave_nueva, $correo);
        if ($updateClave->execute()) {
            // Enviar el nuevo código por correo
            if (enviarCorreoValidacion($correo, $clave_nueva)) {
                echo "Se ha enviado un nuevo código a tu correo.";
            } else {
                echo "Error al enviar el correo.";
            }
        } else {
            echo "Error al actualizar la clave en la base de datos.";
        }
    } else {
        echo "El correo no está registrado.";
    }
} else {
    echo "No se especificó un correo.";
}
?>


