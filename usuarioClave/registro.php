<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Registro de Usuario</h2>
        <form action="enviocorreo.php" method="POST">
            <label for="nombre_usuario">Nombre:</label>
            <input type="text" name="nombre_usuario" required>
            
            <label for="email">Correo Electrónico:</label>
            <input type="email" name="email" required>

            <button type="submit">Registrarse</button>
        </form>
    </div>
</body>
</html>
