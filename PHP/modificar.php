<?php
    //Activamos sesion e incluimos archivo externo para la conexion a la BD.
    session_start();
    include_once('conexionDB.php');
    //Creamos esta variable booleana para controlar el formulario a mostrar que inicializamos en false.
    $Form_socio = false;
    try{
        //Condicion para verificar si el dato recibido en la URL por el metodo GET, es para actualizar la tabla socios.
        if(isset($_GET['socio_id'])){
            //Variable para controlar la condicion booleana en los formularios.
            $Form_socio = true;
            //Guardamos el dato con el ID para saber que socio actualizar
            $idSocio = $_GET['socio_id'];
            //Nos conectamos a la BD
            $conexion = conectarDB();

            //Comprobamos que se envio el formulario modificar socio por POST con los datos a actualizar.
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $nombre = $_POST['nombre'];
                $apellidos = $_POST['apellidos'];
                $ciudad = $_POST['ciudad'];
        
                //Preparamos la consulta preparada para poder actualizar la tabla socios, asignamos con bind el valor a los parametros de la consulta y ejecutamos.
                $consultaUpdate = $conexion->prepare('UPDATE socios SET nombre = :nombre, apellidos = :apellidos, ciudad = :ciudad WHERE socio_id = :socio_id');
                $consultaUpdate->bindParam(':socio_id', $idSocio);
                $consultaUpdate->bindParam(':nombre', $nombre);
                $consultaUpdate->bindParam(':apellidos', $apellidos);
                $consultaUpdate->bindParam(':ciudad', $ciudad);
                $consultaUpdate->execute();

                header('Location: principal.php');
                exit();
            }            
        
        //Condicion por si el dato recibido en la URL por el metodo GET, es para actualizar la tabla vehiculos.
        }elseif(isset($_GET['vehiculo_id'])){
            //Guardamos el dato ID para saber que vehiculo modificar.
            $idVehiculo = $_GET['vehiculo_id'];
            //Nos conectamos a la BD
            $conexion = conectarDB();

            //Comprobamos que se envio el formulario modificar vehiculo por POST con los datos a actualizar.
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $precio = $_POST['precio'];
                $idSocio = $_POST['num-socio'];
        
                //Preparamos la consulta preparada para actualizar la tabla vehiculos, asignamos con bind el valor a los parametros de la consulta y ejecutamos.
                $consultaUpdate = $conexion->prepare('UPDATE vehiculos SET precio = :precio, socio_id = :socio_id WHERE vehiculo_id = :vehiculo_id');
                $consultaUpdate->bindParam(':vehiculo_id', $idVehiculo);
                $consultaUpdate->bindParam(':precio', $precio);
                $consultaUpdate->bindParam(':socio_id', $idSocio);
                $consultaUpdate->execute();

                header('Location: principal.php');
                exit();
            }            
            
        }else{
            //Si entra por aqui es que no llegaron por GET (en la URL) ningun dato.
            echo "<div class='error'>";
            echo "<p>Error: No se encontró un ID válido. Inténtelo de nuevo.</p>";     
            echo "</div>";
            header('Location: principal.php');
            exit();
        }

    }catch (PDOException $e){
        //Aqui manejamos el error de la BD.
        echo "Error: " . $e->getMessage(); 
    } finally {
        //Hemos introducido el bloque finally en el que vamos a cerrar tanto la consulta como la conexion para liberar recursos en memoria.
        $consultaUpdate = null;
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
    <link rel="stylesheet" type="text/css" href="../CSS/estilo_formulario.css">
    <script>
    function volver() {
        // Introducir la URL de la página a la que deseas volver
        window.location.href = 'principal.php';
    }
    </script>
</head>
<body>
    <!-- Formularios para modificar datos de las tabla socios y vehiculos. Lo gestionamos con un if y una condicion booleana 
         que nos mostrara un formulario u otro en funcion de esa condicion. -->
    <?php if ($Form_socio): ?>
    <section>
        <div class="container">
            <div class="formulario">
                <!-- Formulario de modificacion de la tabla socio que envia los datos mediante POST. Preferimos no hacer visible la eleccion del usuarion.
                     Con los campos nombre, apellidos y ciudad. 
                     Controlamos en lado cliente con el tipo de dato (type) y los campos obligatorios (required) -->
                <form action="" method="post">
                    <h4>¡CLUB MOTOR CAFE RACER!</h4>
                    <hr>                   
                    <label>Introduce los datos a modificar en el socio <?=$idSocio?></label>
                    <br>
                    <label>Nombre</label>
                    <input type="text" name="nombre" required="required"/>
                    <br>
                    <label>Apellidos</label>
                    <input type="text" name="apellidos" required="required"/>
                    <br>
                    <label>Ciudad</label>
                    <input type="text" name="ciudad" required="required"/>
                    <br>
                    <br>
                    <input type="submit" name="actualizar" value="ACTUALIZAR"/>
                    <input type="reset" name="borrar" value="BORRAR"/>
                    <input type="button" value="PRINCIPAL" onclick='volver()'/>
                </form>
            </div>
        </div>
    </section>
    <?php else: ?> 
    <section>
        <div class="container">
            <div class="formulario">
                <!-- Formulario de modificacion de la tabla vehiculos que envia los datos a traves de POST ocultando asi los datos al ojo del usuario. 
                     Con los campos precio e id_socio. Controlamos del lado
                     cliente con el tipo de dato introducido (type) y el campo obligatorio (required) -->
                <form action="" method="post">
                    <h4>¡CLUB MOTOR CAFE RACER!</h4>
                    <hr>                   
                    <label>Introduce los datos para modificar el vehiculo <?=$idVehiculo?></label>
                    <br>
                    <label>Precio</label>
                    <input type="text" name="precio" required="required"/>
                    <br>
                    <label>ID Socio</label>
                    <input type="text" name="num-socio" required="required"/>
                    <br>
                    <br>
                    <input type="submit" name="actualizar" value="ACTUALIZAR"/>
                    <input type="reset" name="borrar" value="BORRAR"/>
                    <input type="button" value="PRINCIPAL" onclick='volver()'/>
                </form>
            </div>
        </div>
    </section> 
    <?php endif; ?>                   
</body>
</html>
