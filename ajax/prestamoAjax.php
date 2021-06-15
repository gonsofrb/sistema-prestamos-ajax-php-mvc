<?php

    $peticionAjax=true;
    require_once '../config/APP.php';

        
    if(isset($_POST['buscar_cliente']) || isset($_POST['id_agregar_cliente']) || isset($_POST['id_eliminar_cliente']) || isset($_POST['buscar_item']) || isset($_POST['id_agregar_item']) || isset($_POST['id_eliminar_item']) || isset($_POST['prestamo_fecha_inicio_reg'])){

        /*-------Instancia al controlador--------*/
        require_once '../controladores/prestamosControlador.php';
        $inst_prestamo = new prestamosControlador();

        /*-------Buscar cliente--------*/
        if(isset($_POST['buscar_cliente'])){
            echo $inst_prestamo->buscar_cliente_prestamos_controlador();
        }

        /*-------Agregar cliente--------*/
        if(isset($_POST['id_agregar_cliente'])){
            echo $inst_prestamo->agregar_cliente_prestamos_controlador();
        }

        /*-------Eliminar cliente--------*/
        if(isset($_POST['id_eliminar_cliente'])){
            echo $inst_prestamo->eliminar_cliente_prestamos_controlador();
        }

        
        /*-------Buscar item--------*/
        if(isset($_POST['buscar_item'])){
            echo $inst_prestamo->buscar_item_prestamos_controlador();
        }

        /*-------Agregar item--------*/
        if(isset($_POST['id_agregar_item'])){
            echo $inst_prestamo->agregar_item_prestamos_controlador();
        }

        /*-------Eliminar item--------*/
        if(isset($_POST['id_eliminar_item'])){
            echo $inst_prestamo->eliminar_item_prestamos_controlador();
        }

        /*-------Agregar prestamo--------*/
        if(isset($_POST['prestamo_fecha_inicio_reg'])){
            echo $inst_prestamo->agregar_prestamo_controlador();
        }
        
        
        
        
    }else{//si alguien intenta acceder al archivo loginAjax por la url
                                //SPM->SISTEMA PRETAMOS MOBILIARIO
        session_start(['name'=>'SPM']);//Le damos un nombre a la sesión.
        session_unset();//vaciamos la sesión
        session_destroy();//destruimos la sesión para eliminar todas la variables
        header("Location: ".SERVER_URL."login/");
        exit();
    }