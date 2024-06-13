<?php
    //Activamos sesion e incluimos archivo externo para la conexion a la BD.
    session_start();
    include_once('conexionDB.php');
    //Creamos esta variable booleana para controlar el formulario a mostrar y la inicializamos en false.
    $Form_socio = false;
    try{
        //Condicion por si el dato recibido de la tabla ver socios es para la tabla socios. Lo controlamos con una sesion creada en el switch del archivo mostrar_principal.
        if($_SESSION['insertar'] === 'ver-socios'){
            //Variable para controlar la condicion booleana en los formularios.
            $Form_socio = true;
            //Nos conectamos a la BD
            $conexion = conectarDB();

            // Con el metodo isset() verificamos si se han enviado los campos y si tienen valor.
            if (isset($_POST['nombre']) && isset($_POST['apellidos']) && isset($_POST['insertar'])){
                //Guardamos la credenciales obtenidas en dos variables
                $nombre = $_POST['nombre'];
                $apellidos = $_POST['apellidos'];
                $ciudad = $_POST['ciudad'];
                
                //Consulta donde insertamos el nuevo socio en la BD.
                $consultaInsert = $conexion->prepare("INSERT INTO socios (nombre, apellidos, ciudad) VALUES ( :nombre, :apellidos, :ciudad)"); 
                //Asignamos los parametros del registro a la consulta mediante Bind.
                $consultaInsert->bindParam(":nombre", $nombre);
                $consultaInsert->bindParam(":apellidos", $apellidos);
                $consultaInsert->bindParam(":ciudad", $ciudad);
                //Ejecutamos la consulta para introducir los datos.
                $consultaInsert->execute();
                //Redirigimos a la pagina login.
                header("Location: principal.php");
                exit();
            }          
        
            //Condicion por si el dato recibido de la tabla ver vehiculos es para la tabla vehiculos. Lo controlamos con una sesion creada en el switch del archivo mostrar_principal.
        }elseif($_SESSION['insertar'] === 'ver-vehiculos'){
            
            //Nos conectamos a la BD
            $conexion = conectarDB();

           // Con el metodo isset() verificamos si se han enviado los campos de usuario y contraseña y si tienen valor.
           if (isset($_POST['marca']) && isset($_POST['modelo']) && isset($_POST['insertar'])){
                //Guardamos los datos obtenidos en variables.
                $marca = $_POST['marca'];
                $modelo = $_POST['modelo'];
                $precio = $_POST['precio'];
                $socioID = $_POST['id-socio'];

                //En estas lineas de codigo procesamos la imagen.
                //Recogemos el nombre de la imagen con extencion incluida.
                $nomImg = $_FILES['imagen']['name'];
                //Recogemos la ubicacion temporal del archivo
                $temImg = $_FILES['imagen']['tmp_name'];
                //Recogemos la ruta donde se guardara la foto en el servidor.
                $rutaImg = '../IMG/FOTOS/' . $nomImg;
                //Aqui movemos el la imagen de la ubicacion temporal a la ubicacion definitiva en el servidor.
                move_uploaded_file($temImg, $rutaImg);
                
                //Consulta donde insertamos el nuevo vehiculo en la BD.
                $consultaInsert = $conexion->prepare("INSERT INTO vehiculos (marca, modelo, precio, imagen, socio_id ) VALUES ( :marca, :modelo, :precio, :imagen, :socio_id)"); 
                //Asignamos los parametros del registro a la consulta mediante Bind.
                $consultaInsert->bindParam(":marca", $marca);
                $consultaInsert->bindParam(":modelo", $modelo);
                $consultaInsert->bindParam(":precio", $precio);
                $consultaInsert->bindParam(":imagen", $nomImg);
                $consultaInsert->bindParam(":socio_id", $socioID);
                //Ejecutamos la consulta para introducir los datos.
                $consultaInsert->execute();
                //Redirigimos a la pagina login.
                header("Location: principal.php");
                exit();
            }       
            
        }else{
            //Si entra por aqui es que no llegaron por GET (en la URL) ningun dato.
            echo "<div class='error'>";
            echo "<p>Error: Rellene los campos del formulario.</p>";     
            echo "</div>";
        }

    }catch (PDOException $e){
        //Aqui manejamos el error de la BD.
        echo "Error: " . $e->getMessage(); 
    } finally {
        //Hemos introducido el bloque finally en el que vamos a cerrar tanto la consulta como la conexion para liberar recursos en memoria.
        $consultaInsert = null;
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
    <!-- Formularios para insertar datos de las tabla socios y vehiculos. Lo gestionamos con un if y una condicion booleana 
         que nos mostrara un formulario y otro en funcion de esa condicion. Si la condicion es true mostrara el formulario
        para insertar un socio y si es false mostrara el formulario insertar vehiculo. -->
    <?php if ($Form_socio): ?>
    <section>
        <div class="container">
            <div class="formulario">
                <!-- Formulario para insertar nuevos socios en el que se envian los datos por POST. Con los campos nombre, apellidos y ciudad. Controlamos el formulario
                en el lado cliente mediante el tipo de dato introducido (type) y el campo obligatorio (required) -->
                <form action="" method="post">                   
                    <h4>¡CLUB MOTOR CAFE RACER!</h4>
                    <hr>                   
                    <label>Introduce los datos del nuevo socio</label>
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
                    <input type="submit" name="insertar" value="INGRESAR"/>
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
                <!-- Formulario para insertar nuevos vehiculos en el que se envian los datos por POST. 
                     Con los campos marca, modelo, precio, socio id, imagen. Controlamos
                     el formulario en el lado cliente mediante el tipo de dato introducido (type) y los campos obligatorios (required). -->
                <form action="" method="post" enctype="multipart/form-data">
                    <h4>¡CLUB MOTOR CAFE RACER!</h4>
                    <hr>                   
                    <label>Introduce los datos del nuevo vehiculo</label>
                    <br>
                    <label>Marca</label>
                    <input type="text" name="marca" required="required"/>
                    <br>
                    <label>Modelo</label>
                    <input type="text" name="modelo" required="required"/>
                    <br>
                    <label>Precio</label>
                    <input type="text" name="precio" required="required"/>
                    <br>
                    <label>Socio ID</label>
                    <input type="text" name="id-socio" required="required"/>
                    <br>
                    <label>Imagen</label>
                    <input type="file" name="imagen" accept="image/*" required="required"/>
                    <br>
                    <br>
                    <input type="submit" name="insertar" value="INGRESAR"/>
                    <input type="reset" name="borrar" value="BORRAR"/>
                    <input type="button" value="PRINCIPAL" onclick='volver()'/>
                </form>
            </div>
        </div>
    </section> 
    <?php endif; ?>                   
</body>
</html>