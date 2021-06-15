<?php
//? ESTE ARCHIVO SE VA A EN ENCARGA DE RECIBIR LO DATOS QUE SON ENVIADOS DESDE EL FORMULARIO ITEM
    $peticionAjax=true;
    require_once '../config/APP.php';

      if(isset($_POST['item_codigo_reg']) || isset($_POST['item_id_del']) || isset($_POST['item_id_up'])){

        /******Instancia al controlador******/
        require_once '../controladores/itemControlador.php';
        $inst_item = new itemControlador();

        /****Agregar Item*******/
        if(isset($_POST['item_codigo_reg'])){
          echo $inst_item->agregar_item_controlador();
        }
        
        /****Eliminar Item*******/
        if(isset($_POST['item_id_del'])){
          echo $inst_item->eliminar_item_controlador();
        }

        /****Actualizar Item*******/
        if(isset($_POST['item_id_up'])){
          echo $inst_item->actualizar_item_controlador();
        }

       
    }else{//si alguien intenta acceder al archivo usuarioAjax por la url
                                //SPM->SISTEMA PRETAMOS MOBILIARIO
        session_start(['name'=>'SPM']);//Le damos un nombre a la sesión.
        session_unset();//vaciamos la sesión
        session_destroy();//destruimos la sesión para eliminar todas la variables
        header("Location: ".SERVER_URL."login/");
        exit();
    }