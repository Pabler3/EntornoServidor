<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Club Motor</title>
</head>
<body>
    <!-- Aqui cerramos todas las sesiones existentes. -->
    <?php
        //activamos sesiones
        session_start();
        //destruimos la sesion
        session_destroy();
        //redirigimos al login
        header('Location: ../Login.php');
        exit();
    ?>
    
</body>
</html>