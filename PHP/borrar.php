<?php
    //Activamos sesion e incluimos archivo externo para la conexion a la BD.
    session_start();
    include_once('conexionDB.php');
    try{
        //Condicion por si el dato recibido en la URL de la eleccion del usuario en la tabla ver socios es para borrar de la tabla socios.
        if(isset($_GET['socio_id'])){
        
            //Guardamos el dato con el ID para saber que socio borrar.
            $idSocio = $_GET['socio_id'];
            //Nos conectamos a la BD
            $conexion = conectarDB();

            //Preparamos la consulta preparada para poder borrar de la tabla socios al socio correspondiente segun ID.
            $consultaDelete = $conexion->prepare('DELETE FROM socios WHERE socio_id = :socio_id');
            //Asignamos el valor al parametro de la consulta mediante bind y ejecutamos.
            $consultaDelete->bindParam(':socio_id', $idSocio);
            $consultaDelete->execute();

            header('Location: principal.php');
            exit();
                        
        
        //Condicion por si el dato recibido en la URL de la eleccion del usuario en la tabla ver vehiculoses para borrar de la tabla vehiculos.
        }elseif(isset($_GET['vehiculo_id'])){
            //Guardamos el dato con el ID para saber que vehiculo borrar. 
            $idVehiculo = $_GET['vehiculo_id'];
            //Nos conectamos a la BD
            $conexion = conectarDB();

            //Preparamos la consulta preparada para borrar de la tabla vehiculos el vehiculo correspondiente segun ID.
            $consultaDelete = $conexion->prepare('DELETE FROM vehiculos WHERE vehiculo_id = :vehiculo_id');
            //Asignamos el valor al parametro de la consulta y ejecutamos.
            $consultaDelete->bindParam(':vehiculo_id', $idVehiculo);
            $consultaDelete->execute();

            header('Location: principal.php');
            exit();
                        
            
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
        $consultaDelete = null;
        $conexion = null;

    }

?>