<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado del mensaje del formulario</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="popup">
        <?php
        if($_GET['status'] === 'success') {
            echo '<p class="success"><i class="fa fa-check-circle"></i> Formulario enviado con éxito</p>';
        } else {
            echo '<p class="error"><i class="fa fa-times-circle"></i> Formulario no enviado debido a un error de conexión </p>';
        }
        ?>
        <a href="index.html" class="btn"> Volver </a>
    </div>
</body>
</html>