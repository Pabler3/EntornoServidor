<?php

function contadorVisitas(){ 
    //Vamos a crear una cookie en la que vamos a contar las veces que el usuario visita las paginas que estimemos oportuno.
    if (!isset($_COOKIE['contador_visita'])){
        //Si no esta definida la cookie la inicializamos a 1, es decir, el usuario no ha visitado la pagina.
        $contador = 1;
    }else {
        //Si la cookie esta definida incrementamos su valor, es decir, el usuario ya ha visitado la pagina.
        $contador = $_COOKIE['contador_visita'] + 1;
    }

    //Creamos la cookie con el nuevo valor. Asi nos aseguramos que el contador se ha incrementado en lugar de reiniciarse cada vez
    //Que el usuario visita la pagina en concreto. En este caso la cookie la mantenemos durante 1 dia.
    setcookie('contador_visita', $contador, time() + 86400, "/");
    //Devolvemos el valor actualizado.
    return $contador;
    
}
?>