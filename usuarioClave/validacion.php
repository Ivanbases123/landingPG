<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo = $_POST['correo'];
    $clave = $_POST['clave'];

    $stmt = $conn->prepare("SELECT correo FROM usuarios WHERE correo = ? AND clave_validacion = ?");
    $stmt->bind_param("ss", $correo, $clave);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $update = $conn->prepare("UPDATE usuarios SET estado = 1 WHERE correo = ?");
        $update->bind_param("s", $correo);
        $update->execute();

        echo "Usuario validado correctamente.";
    } else {
        echo "Clave de validación incorrecta. Se enviará un nuevo código.";
        $clave_nueva = rand(100000, 999999);
        $updateClave = $conn->prepare("UPDATE usuarios SET clave_validacion = ? WHERE correo = ?");
        $updateClave->bind_param("ss", $clave_nueva, $correo);
        $updateClave->execute();

        // Enviar nuevamente el correo con la nueva clave
        // (Reutilizar código de `enviocorreo.php`)
    }
}
?>
