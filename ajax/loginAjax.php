<?php

    $peticionAjax=true;
    require_once '../config/APP.php';

        
    if(isset($_POST['token']) && isset($_POST['usuario'])){

        /*-------Instancia al controlador--------*/
        require_once '../controladores/loginControlador.php';
        $inst_login = new loginControlador();
        echo $inst_login->cerrar_sesion();
        
        
    }else{//si alguien intenta acceder al archivo loginAjax por la url
                                //SPM->SISTEMA PRETAMOS MOBILIARIO
        session_start(['name'=>'SPM']);//Le damos un nombre a la sesión.
        session_unset();//vaciamos la sesión
        session_destroy();//destruimos la sesión para eliminar todas la variables
        header("Location: ".SERVER_URL."login/");
        exit();
    }