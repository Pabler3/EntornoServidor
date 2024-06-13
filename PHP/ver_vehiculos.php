<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Club Motor</title>
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
                        //Activamos sesion e incluimos archivo externo para la conexion a la BD.
                        session_start();
                        include_once('conexionDB.php');
                        try{
                            //Comprobamos si el socio_id nos llega por GET, atraves de la URL. Este dato viene de la seleccion del usuario
                            //En la tabla Ver Socios para ver los vehiculos de un determinado socio.
                            if(isset($_GET['socio_id'])){
                                //Guardamos el dato 
                                $idSocio = $_GET['socio_id'];
                                //Nos conectamos a la BD
                                $conexion = conectarDB();
                                //Preparamos la consulta preparada para poder obtener los vehiculos que tiene un socio en concreto.
                                $consulta = $conexion->prepare('SELECT * FROM vehiculos WHERE socio_id = :socio_id');
                                //Asignamos el valor a los parametros de la consulta y ejecutamos.
                                $consulta->bindParam(':socio_id', $idSocio);
                                $consulta->execute();

                                //Mostramos los datos en una tabla
                                echo "<table border='1px solid' align='center'class='tabla'>";
                                echo "<tr><td>" . 'ID VEHICULO' . "</td><td>" . 'MARCA' . "</td><td>" . 'MODELO' . "</td><td>" . 'PRECIO' . "</td><td>" . 'IMAGEN' . "</td></tr>";
                                //Vamos recorriendo con un bucle while el array asociativo con los resultados almacenados en fectch() y los vamos mostrando en la tabla.
                                while($columna=$consulta->fetch(PDO::FETCH_ASSOC)){
                                    echo "<tr><td>{$columna['vehiculo_id']} 
                                        </td><td>{$columna['marca']} 
                                        </td><td>{$columna['modelo']} 
                                        </td><td>{$columna['precio']} 
                                        </td><td><img src='../IMG/FOTOS/{$columna['imagen']}'></td></tr>"; 
                                }
                                echo "</table>";
                                
                                // Agregamos un botón para volver a otra página.
                                echo "<button onclick='volver()'>VOLVER PRINCIPAL</button>";
                                //Si entra por aqui es que el usuario a rellenado el formulario para ver los vehiculos de una determinada marca.
                                //El formulario lo manda la pagina mostrar_principal.php
                            }elseif (isset($_GET['marca'])){
                                //Guardamos el dato en una variable
                                $marca = $_GET['marca'];
                                //Nos conectamos a la BD.
                                $conexion = conectarDB();
                                
                                //Preparamos la consulta preparada para poder obtener los vehiculos que tiene un socio en concreto.
                                $consulta = $conexion->prepare('SELECT * FROM vehiculos WHERE marca = :marca');
                                //Asignamos el valor al parametro de la consulta y ejecutamos.
                                $consulta->bindParam(':marca', $marca);
                                $consulta->execute();
                                //Vamos a verificar que la marca que ha introducido el usuario coincide con alguna de la BD.
                                if ($consulta->rowCount() > 0){
                                    //Mostramos los datos en una tabla
                                    echo "<table border='1px solid' align='center'class='tabla'>";
                                    echo "<tr><td>" . 'ID VEHICULO' . "</td><td>" . 'MARCA' . "</td><td>" . 'MODELO' . "</td><td>" . 'PRECIO' . "</td><td>" . 'IMAGEN' . "</td></tr>";
                                    //Vamos recorriendo con un bucle while el array asociativo con los resultados almacenados en fectch() y los vamos mostrando en la tabla.
                                    while($columna=$consulta->fetch(PDO::FETCH_ASSOC)){
                                        echo "<tr><td>{$columna['vehiculo_id']} 
                                             </td><td>{$columna['marca']} 
                                             </td><td>{$columna['modelo']} 
                                             </td><td>{$columna['precio']} 
                                             </td><td><img src='../IMG/FOTOS/{$columna['imagen']}'></td></tr>"; 
                                    }

                                    echo "</table>";
                                    // Agregamos un botón para volver a otra página.
                                    echo "<button onclick='volver()'>VOLVER PRINCIPAL</button>";
                                }else{
                                    //Si entra por aqui en la BD no habria ninguna marca coincidente con la introducida.
                                    echo "<div class='error'>";
                                    echo "<p>En nuestra exposición no hay vehiculos con la marca <p>" . $marca;
                                    echo "</div>";
                                    // Agregamos un botón para volver a otra página.
                                    echo "<button onclick='volver()'>VOLVER PRINCIPAL</button>";
                                }
                                

                                
                            }else{
                                //Si entra por aqui es que no llegaron por GET (en la URL) el dato socio_id.
                                "<div class='error'>";
                                    echo "<p>Error: No se encontró un ID válido. Inténtelo de nuevo.</p>";     
                                "</div>";
                                header('Location: principal.php');
                                exit();
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