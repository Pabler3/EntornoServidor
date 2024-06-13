
<?php
// Hemos creado una funcion para conectar a la base de datos en un fichero que iremos incluyendo donde lo necesitemos 
function conectarDB(){
    //Creamos las variables de conexion con la dsn, el usuario y la contraseña.
    $dsn = "mysql:host=localhost;dbname=club_motor";
    $usuario = "root";
    $contrasena = "";
    //Mediante el bloque try-catch vamos a manejar la conexion que en este caso la haremos mediante PDO y los posibles errores.
    try{
        //Creamos el objeto para realizar la conexion.
        $conexion = new PDO($dsn, $usuario, $contrasena);
        //Con esta linea estamos diciendo que si hay algún problema que salte la excepción.
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conexion;
        
    }catch (PDOException $e){ //Hemos creado un objeto tipo exception para asi poder trabajar con el la excepcion, si saltara, mandariamos el mensaje de error.
        die("Error al conectar con la base de datos: " . $e->getMessage());
    }
}

?> 