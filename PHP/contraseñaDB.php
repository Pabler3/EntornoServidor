<?php
    /* Vamos a crear una funcion para encriptar las contraseñas y proporcionar mas seguridad al proyecto, esto implica que tendremos
    que ir dando de alta los usuarios en la base de datos directamente desde el navegador. */

    function hashContrasenia($contrasenia){
        return password_hash($contrasenia, PASSWORD_BCRYPT);
    }
    function verificarContrasenias ($contrasenia, $contraseniaBD){
        return password_verify($contrasenia ,$contraseniaBD);
    }  
?>