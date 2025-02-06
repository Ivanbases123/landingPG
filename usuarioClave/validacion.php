<?php
include '../conexion.php';

$mensaje = ""; // Aquí voy a guardar el mensaje que se mostrará en pantalla

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo = $_POST['email'];
    $clave = $_POST['clave'];

    // Verifico si el código ingresado es correcto
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ? AND clave_asociada = ?");
    $stmt->bind_param("si", $correo, $clave);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Si es correcto, activo la cuenta
        $update = $conn->prepare("UPDATE usuarios SET estado = 1 WHERE email = ?");
        $update->bind_param("s", $correo);
        $update->execute();
        
        // Redirigir al login después de la validación
        header("Location: login.html");
        exit();
    } else {
        // Mensaje para indicar que el código es incorrecto
        $mensaje = "Código incorrecto. Por favor, inténtalo de nuevo.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validación de Cuenta</title>
</head>
<body>
    <h2>Validación de Cuenta</h2>
        <!-- Mensaje informativo -->
    <p style="color: #555; font-size: 14px; text-align: center;">
        Revisa tu bandeja de entrada o carpeta de spam. Este código es necesario para activar tu cuenta.
        Hemos enviado un código de validación a tu correo electrónico. 
    </p>    

    <?php if (!empty($mensaje)) { ?>
        <p style="color: red;"><?php echo $mensaje; ?></p> <!-- Aquí muestro el mensaje si el código es incorrecto -->
    <?php } ?>

    <form action="validacion.php" method="POST">
        <label for="email">Correo Electrónico:</label>
        <input type="email" name="email" required value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">

        <label for="clave">Código de Validación:</label>
        <input type="text" name="clave" required>

        <button type="submit">Validar Cuenta</button>
    </form>

    <p>Si no recibiste el código o lo perdiste, <a href="reenviar_codigo.php?email=<?php echo urlencode($_POST['email'] ?? ''); ?>">haz clic aquí para reenviar</a>.</p>
</body>
</html>




