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
    <section>
        <div class="container">
            <div class="formulario">
                <div class="formulario_">
                    <?php
                        //Iniciamos sesion
                        session_start();
                        
                        //Incluimos el archivo para la conexion a la BD.
                        include_once('conexionDB.php');
                        try{
                            //Comprobamos que los datos enviados por el formulario principal, llegan por el metodo GET que es el indicado para este caso.
                            if ($_SERVER['REQUEST_METHOD'] == 'GET'){
                                //Nos conectamos a la BD.
                                $conexion = conectarDB();
                                //Guardamos el dato introducido en el formulario para despues trabajar con el.
                                $accion = $_REQUEST['accion'];
                                //Creamos sesion insertar para controlar la accion que pulsa el usuario. Despues la utilizaremos a la hora de mostrar datos.
                                $_SESSION['insertar'] = $accion;
                                //Mediante un switch vamos a elegir que haremos.
                                switch($accion){
                                    case "ver-vehiculos":
                                        //Consulta preparada correspondiente para esta accion
                                        $consulta = $conexion->prepare('SELECT * FROM vehiculos');
                                        //Ejecutamos la consulta
                                        $consulta->execute();
                                        //Creamos una tabla para cuando mostremos los datos.
                                        echo "<table border='1px solid' align='center'class='tabla'>";
                                        echo "<tr><td>" . 'ID' . "</td><td>" . 'MARCA' . "</td><td>" . 'MODELO' . "</td><td>" . 'PRECIO' . "</td><td>" . 'IMAGEN' . "</td><td>" . 'MODIFICAR' . "</td><td>" . 'BORRAR' . "</td></tr>";
                                        //Guardamos los datos recogidos de la BD.
                                        $resultados = $consulta->fetchAll();
                                        //Recorremos con un foreach el array asociativo que hemos obtenido de la sentencia y vamos mostrando datos en la tabla.
                                        foreach ($resultados as $posicion => $columna){
                                           
                                            echo "<tr><td>{$columna['vehiculo_id']} 
                                                  </td><td>{$columna['marca']} 
                                                  </td><td>{$columna['modelo']} 
                                                  </td><td>{$columna['precio']} 
                                                  </td><td><img src='../IMG/FOTOS/{$columna['imagen']}'></td>
                                                  <td><a class='button' href='modificar.php?vehiculo_id={$columna['vehiculo_id']}'>Modificar</a></td>
                                                  <td><a class='button' href='borrar.php?vehiculo_id={$columna['vehiculo_id']}'>Borrar</a></td></tr>";
                                            
                                        }
                                        echo "</table>";
                                        // Agregamos un botón para volver a otra página.
                                        echo "<button onclick='volver()'>VOLVER PRINCIPAL</button>";
                                        // Este boton nos redirecciona a crear un vehiculo nuevo
                                        echo "<button><a href='insertar.php'>NUEVO VEHICULO</a></button>";
                                        // Creamos un pequeño formulario por si el usuario quiere ver los vehiculos de una determinada marca. En este caso lo hacemos por GET porque
                                        // Necesitamos rescatar el dato enviado a traves de la URL, como es un campo que no lleva informacion delicada es valida esta opcion.
                                        echo "<form action='ver_vehiculos.php' method='get'>";
                                        echo "<label>Ver Vehiculos de la marca</label>";
                                        echo "<input type='text' name='marca' pattern='[A-Z][a-zA-Z]*' title='Debe empezar con mayúscula'><br>";
                                        echo "<input type='submit' name='enviar' class='button' value='VER'/>";
                                        break;
                                    case "ver-socios":
                                        //Consulta preparada correspondiente a esta accion.
                                        $consulta = $conexion->prepare('SELECT * FROM socios');
                                        //Ejecutamos consulta.
                                        $consulta->execute();
                                        //Creamos tabla para mostrar los datos.
                                        echo "<table border='1px solid' align='center'class='tabla'>";
                                        echo "<tr><td>" . 'ID SOCIO' . "</td><td>" . 'NOMBRE' . "</td><td>" . 'APELLIDOS' . "</td><td>" . 'CIUDAD' . "</td><td>" . 'SUS VEHICULOS' . "</td><td>" . 'MODIFICAR' . "</td><td>" . 'BORRAR' . "</td></tr>";
                                        //Guardamos los datos recogidos de la BD.
                                        $resultados = $consulta->fetchAll();
                                        //Recorremos con un foreach el array asociativo con los resultados obtenidos por la sentencia y vamos mostrando datos en la tabla.
                                        foreach ($resultados as $posicion => $columna){
                                           
                                            echo "<tr><td>{$columna['socio_id']} 
                                                  </td><td>{$columna['nombre']} 
                                                  </td><td>{$columna['apellidos']} 
                                                  </td><td>{$columna['ciudad']} 
                                                  </td><td><a class='button' href='ver_vehiculos.php?socio_id={$columna['socio_id']}'>Ver Vehículos</a>
                                                  </td><td><a class='button' href='modificar.php?socio_id={$columna['socio_id']}'>Modificar</a></td>
                                                  <td><a class='button' href='borrar.php?socio_id={$columna['socio_id']}'>Borrar</a></td></tr>";
                                                
                                        }
                                        
                                        echo "</table>";
                                        // Agregamos un botón para volver a otra página.
                                        echo "<button onclick='volver()'>VOLVER PRINCIPAL</button>";
                                        // Agregamos un boton que redirecciona a crear un nuevo socio.
                                        echo "<button><a href='insertar.php'>NUEVO SOCIO</a></button>";
                                        break;
                                        
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
                </div>
            </div>
        </div>
    </section>
</body>
</html>
