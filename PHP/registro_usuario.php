<?php
    //Incluimos el archivo para la conexion a la BD y el archivo para verificar contraseñas.
    include_once('conexionDB.php');
    include_once('contraseñaDB.php');
    //Mediante el bloque try-catch vamos hacer la verificacion de las credenciales con la BD, asi controlaremos un posible error.
    try{    
    
        //Comprobamos si los datos enviados en el formulario de registro de usuario nuevo nos llegan por el metodo POST.
        if ($_SERVER['REQUEST_METHOD']== 'POST'){
            
            //Llamamos a la funcion conectarDB() para conectarnos a la BD.
            $conexion = conectarDB();
            // Con el metodo isset() verificamos si se han enviado los campos de usuario y contraseña y si tienen valor.
            if (isset($_POST['email']) && isset($_POST['contrasena']) && isset($_POST['registrarse'])){
                //Guardamos la credenciales obtenidas en dos variables
                $email = $_POST['email'];
                $contrasenia = htmlspecialchars($_POST['contrasena']);
                $confirmar_contrasenia = htmlspecialchars($_POST['confirmar_contrasena']);
                //Validamos el formato del email
                if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                    $error_message = "El formato del correo electronico no es valido. Inténtelo de nuevo";
                }else{ 
                    //Vamos a proceder a comprobar que las dos contraseñas introducidas son iguales y encryptamos la contraseña con la funcion hash.
                    if($contrasenia === $confirmar_contrasenia){
                        $contrasenia_encryptada = hashContrasenia($contrasenia);
                        //Consulta preparada donde insertamos el nuevo usuario en la BD.
                        $consulta = $conexion->prepare("INSERT INTO usuarios (username, contrasenia) VALUES ( :correo, :contrasena)"); 
                        //Asignamos valor a los parametros de la consulta del registro mediante Bind.
                        $consulta->bindParam(":correo", $email);
                        $consulta->bindParam(":contrasena", $contrasenia_encryptada);
                        //Ejecutamos la consulta para introducir los datos.
                        $consulta->execute();
                        //Redirigimos a la pagina login.
                        header("Location: ../login.php");
                        exit();
                    }else{
                        //Si entra por aqui quiere decir que las contraseñas introducidas no son iguales.
                        $error_message = "Las contraseñas introducidad no coinciden. Inténtelo de nuevo";
                    }
                }   
              
            }else{
                //Si entra por aqui los campos usuario y contraseña no estarian rellenados.
                $error_message = "Por favor, rellene los campos de usuario y contraseña";
            }
        }
   
    }catch(PDOException $e){
        //Desde aqui manejamos las excepciones
        echo "Error: " . $e->getMessage;
    }finally{
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
    <link rel="stylesheet" type="text/css" href="../CSS/estilos_form.css">
     <!-- Enlazamos archivo externo js para iconos del formulario login y registro -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</head>
<body>   
    <section>
        <div class="form-box">
            <div class="form-login">
                <!-- Formulario de registro que envía los datos por POST. Al haber informacion sensible es preciso usar este metodo ya que los datos que se envían son invisibles al ojo del usuario.
                     con los campos email, contraseña, confirmar contraseña y opcion de volver al login. 
                     Este formulario controla en lado cliente mediante el tipo de dato introducito(type), campo obligatorio(required)
                     y longitud minima de la cadena de caracteres minlenght -->
                <form action="#" method="post">
                    <h4>¡CLUB MOTOR CAFE RACER!</h4>
                    <h2>Bienvenido</h2>
                    <div class="input-form">
                        <!-- icono email -->
                        <ion-icon name="mail-outline"></ion-icon>
                        <input type="email" name="email" required="required" minlength="5">
                        <label>Email</label>
                    </div>
                    <div class="input-form">
                        <!-- icono psw -->
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input type="password" name="contrasena" required="required" minlength="8" title="La contraseña debe contener letras y numeros">
                        <label>Contraseña</label>
                    </div>
                    <div class="input-form">
                        <!-- icono psw -->
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input type="password" name="confirmar_contrasena" required="required" minlength="8" title="La contraseña debe contener letras y numeros">
                        <label>Confirmar Contraseña</label>
                    </div>
                    <button type="submit" name="registrarse" value="Registrarse">Registrarse</button>
                    <div class="register">
                        <p>Ya tengo una cuenta <a href="../login.php">Iniciar sesion</a></p>
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