<?php
//? ESTE ARCHIVO SE VA A EN ENCARGA DE RECIBIR LO DATOS QUE SON ENVIADOS DESDE LOS FORMULARIOS DEL CLIENTE
    $peticionAjax=true;
    require_once '../config/APP.php';

       //Comprobación si se actualiza-ingresar-eliminar
    if(isset($_POST['cliente_dni_reg']) || isset($_POST['cliente_id_del']) || isset($_POST['cliente_id_up'])){

        /*-------Instancia al controlador--------*/
        require_once '../controladores/clienteControlador.php';
        $inst_cliente = new clienteControlador();

        /*-------Agregar un cliente--------*/
        if(isset($_POST['cliente_dni_reg']) && isset($_POST['cliente_nombre_reg'])){
        echo $inst_cliente->agregar_cliente_controlador();
        }

       /*-------Eliminar un cliente--------*/
       if(isset($_POST['cliente_id_del'])){
        echo $inst_cliente->eliminar_cliente_controlador();
        }

        /*-------Actualizar un cliente--------*/
        if(isset($_POST['cliente_id_up'])){
            echo $inst_cliente->actualizar_cliente_controlador();
            }
        
    }else{//si alguien intenta acceder al archivo usuarioAjax por la url
                                //SPM->SISTEMA PRETAMOS MOBILIARIO
        session_start(['name'=>'SPM']);//Le damos un nombre a la sesión.
        session_unset();//vaciamos la sesión
        session_destroy();//destruimos la sesión para eliminar todas la variables
        header("Location: ".SERVER_URL."login/");
        exit();
    }