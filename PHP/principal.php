<?php
    //Activamos la sesion
    session_start();
    //Comprobamos que las sesiones existen, es decir, verificamos que el cliente se ha logueado correctamente.
    if(!isset($_SESSION['id']) && !isset($_SESSION['usuario'])){
        //Si no existen lo redirigimos
        header('Location: ../login.php');
        exit();
    }else{
        //Incluimos el archivo para el controlar las veces que el usuario a pasado por esta pagina.
        include_once('controlVisitas.php');
        //Llamamos a la funcion.
        $contadorActualizado = contadorVisitas();
        //Creamos esta variable booleana para controlar si mostramos el formulario. Se mostrara siempre que el usuario se haya
        //Logueado y esten las sesiones creadas.
        $Form_principal = true;
     
    }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Club Motor</title>
    <!-- Enlazamos hoja de estilos css para dar una imagen visual a la aplicacion -->
    <link rel="stylesheet" type="text/css" href="../CSS/estilo_formulario.css">
    <script>
    function volver() {
        // Introducir la URL de la página a la que deseas volver
        window.location.href = '../login.php';
    }
    </script>
</head>
<body>
    <?php if ($Form_principal): ?>
    <section>
        <div class="container">
            <div class="formulario">
                <!-- Formulario principal que envía los datos por GET. En caso la seleccion que haga el usuario los datos viajan a traves 
                     de la URL del navegador, quiere decir que son visibles al ojo del usuario, pero como no hay informacion sensible es valida esta opcion.-->
                <form action="mostrar_principal.php" method="get">
                    <h4>¡CLUB MOTOR CAFE RACER!</h4>
                    <h2>Bienvenido <?=$_SESSION['usuario']?>, estas dentro de la mejor exposición de motos cafe racer </h2>
                    <hr>                   
                    <label>¿Qué deseas hacer?</lable>
                    <br>
                    <label>Ver Vehiculos</label>
                    <input type="radio" name="accion" value="ver-vehiculos" required="required"/>
                    <label>Ver Socios</label>
                    <input type="radio" name="accion" value="ver-socios" required="required"/>
                    <br>
                    <br>
                    <input type="submit" name="confirmar" value="ACCEDER"/>
                    <input type="button" value="CERRAR SESION" onclick='volver()'/>
                </form>
            </div>
        </div>
    </section>
    <?php endif; ?>
</body>
</html>