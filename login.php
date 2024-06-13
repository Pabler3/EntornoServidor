<?php 
    
    //Incluimos el archivo para la conexion a la BD y el archivo para verificar contraseñas.
    include_once('PHP/conexionDB.php');
    include_once('PHP/contraseñaDB.php');
    //Mediante el bloque try-catch vamos hacer la verificacion de las credenciales con la BD, asi controlaremos un posible error.
    try{

        //Comprobamos si los datos nos llegan del formulario de login llegan por el metodo POST.
        if ($_SERVER['REQUEST_METHOD']== 'POST'){
            //Guardamos la credenciales obtenidas en dos variables
            $usuario = $_POST['email'];
            $contrasenia = htmlspecialchars($_POST['contrasena']);
            //Llamamos a la funcion conectarDB() para conectarnos a la BD.
            $conexion = conectarDB();
            // Con el metodo isset() verificamos si se han enviado los campos de usuario y contraseña y si tienen valor.
            if (isset($_POST['email']) && isset($_POST['contrasena']) && isset($_POST['login'])){
                //Vamos a preparar una consulta SQL preparada que va a recorrer la tabla usuarios.
                $consulta = $conexion->prepare("SELECT usuario_id, username, contrasenia FROM usuarios WHERE username = :username");
                //Asignamos valor a los parametros de la consulta con Bind
                $consulta->bindParam(':username', $usuario);
                //Ejecutamos la consulta
                $consulta->execute();
                //Guardamos en una variable los datos obtenidos de la BD que nos devuelve la fila de un conjunto de resultados como un array asociativo.
                $row = $consulta->fetch(PDO::FETCH_ASSOC);
                //Vamos a verificar si en algun usuario coinciden las credenciales de la BD con las credenciales proporcionadas.
                if ($row){
                    $contrasenaDB = $row['contrasenia'];
                    if(verificarContrasenias($contrasenia, $contrasenaDB)){
                        //Usuario estaria autentificado correctamente. Creamos la sesion y guardamos los datos en 3 variables superglobales para su posterior utilizacion.
                        session_start();
                        $_SESSION['id'] = $row['usuario_id'];
                        $_SESSION['usuario'] = $row['username'];
                        $_SESSION['contraseña'] = $row['contrasenia'];
                        //Establecemos unas cookies que seran accesibles en todos los directorios una vez que el usuario esta logueado en la que guardaremos el id y usuario por 1 dia.
                        setcookie("usuario_id", $row['usuario_id'], time() + 86400, "/");
                        setcookie("usuario_nombre", $row['username'], time() + 86400, "/");
                        //Redirigimos al usuario a la pagina principal.
                        header('location: PHP/principal.php');
                        die();

                    }else{
                    //Las credenciales son incorrectas. Guardamos el mensaje en una variable.
                    $error_message = "Usuario o contraseña incorrectos";
                    }
                }else{
                    $error_message = "Usuario no encontrado. Registrate para crear una cuenta.";
                }
            }else {
                //Si entra por aqui los campos usuario y contraseña no estarian rellenados.
                $error_message = "Por favor, rellene los campos de usuario y contraseña";
            }
        }


    }catch (PDOException $e){
        //Aqui manejamos el error de la BD.
         echo "Error: " . $e->getMessage(); 
    } finally {
        //Hemos introducido el bloque finally en el que vamos a cerrar tanto la consulta como la conexion para liberar recursos en memoria.
        $consulta = null;
        $conexion = null;
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
    <link rel="stylesheet" type="text/css" href="./CSS/estilos_form.css">
    <!-- Enlazamos archivo externo js para iconos del formulario login y registro -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</head>
<body>   
    <section>
        <div class="form-box">
            <div class="form-login">
                <!-- Formulario de login que envía los datos por POST. Al haber informacion sensible es preciso usar este metodo ya que los datos que se envían son invisibles al ojo del usuario. 
                     con los campos usuario(email), contraseña y opcion de registrar usuario nuevo
                     este formulario controla en lado cliente mediante el tipo de dato introducito(type), campo obligatorio(required)
                     y longitud minima de la cadena de caracteres minlenght -->
                <form action="#" method="post">
                    <h4>¡CLUB MOTOR CAFE RACER!</h4>
                    <h2>Bienvenido</h2>
                    <div class="input-form">
                         <!-- icono email -->
                        <ion-icon name="mail-outline"></ion-icon>
                        <input type="email" name="email" required="required" minlength="5">
                        <label>Usuario</label>
                    </div>
                    <div class="input-form">
                        <!-- icono psw -->
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input type="password" name="contrasena" required="required" minlength="8" title="La contraseña debe contener letras y numeros">
                        <label>Contraseña</label>
                    </div>
                    <button type="submit" name="login" value="login">Login</button>
                    <div class="register">
                        <p>No tengo una cuenta <a href="PHP/registro_usuario.php">Registrarse</a></p>
                    </div>
                    <div class="error">
                        <?php
                            //Mostrariamos mensajes de error si existieran. Lo dejamos fuera del primer condicional if para que asi se ejecute independientemente de como se recojan los datos.      
                            if (isset($error_message)){
                                echo "<p>$error_message</p>";
                            }  
                        ?>
                    </div>
                </form>
            </div>
        </div>
    </section>
</body>
</html>