<?php
//? ESTE ARCHIVO SE VA A EN ENCARGA DE RECIBIR LO DATOS QUE SON ENVIADOS DESDE LOS FORMULARIOS DEL USUARIO
    $peticionAjax=true;
    require_once '../config/APP.php';

        //Comprobación si se actualiza-ingresar-eliminar
    if(isset($_POST['usuario_dni_reg']) || isset($_POST['usuario_id_delete']) || isset($_POST['usuario_id_up'])){

        /*-------Instancia al controlador--------*/
        require_once '../controladores/usuarioControlador.php';
        $inst_usuario = new usuarioControlador();

        /*-------Agregar un usuario--------*/
        if(isset($_POST['usuario_dni_reg'])){
            echo $inst_usuario->agregar_usuario_controlador();
            
        }

        /*-------Eliminar un usuario--------*/
        if(isset($_POST['usuario_id_delete'])){
            echo $inst_usuario->eliminar_usuario_controlador();
            
        }
        /*-------Actualizar un usuario--------*/
        if(isset($_POST['usuario_id_up'])){
            echo $inst_usuario->actualizar_usuario_controlador();
            
        }
        
    }else{//si alguien intenta acceder al archivo usuarioAjax por la url
                                //SPM->SISTEMA PRETAMOS MOBILIARIO
        session_start(['name'=>'SPM']);//Le damos un nombre a la sesión.
        session_unset();//vaciamos la sesión
        session_destroy();//destruimos la sesión para eliminar todas la variables
        header("Location: ".SERVER_URL."login/");
        exit();
    }