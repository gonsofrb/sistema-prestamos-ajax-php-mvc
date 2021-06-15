<?php
//? ESTE ARCHIVO SE VA A EN ENCARGA DE RECIBIR LO DATOS QUE SON ENVIADOS DESDE EL FORMULARIO EMPRESA
    $peticionAjax=true;
    require_once '../config/APP.php';

      if(isset($_POST['empresa_nombre_reg']) || isset($_POST['empresa_id_up'])){

        /**Instancia al controlador */
        require_once '../controladores/empresaControlador.php';
        $inst_empresa = new empresaControlador();

        /**Agregar empresa */
        if(isset($_POST['empresa_nombre_reg'])){
            echo $inst_empresa->agregar_empresa_controlador();

        }

        /**Actualizar empresa */
        if(isset($_POST['empresa_id_up'])){
            echo $inst_empresa->actualizar_empresa_controlador();

        }

       
    }else{//si alguien intenta acceder al archivo usuarioAjax por la url
                                //SPM->SISTEMA PRETAMOS MOBILIARIO
        session_start(['name'=>'SPM']);//Le damos un nombre a la sesión.
        session_unset();//vaciamos la sesión
        session_destroy();//destruimos la sesión para eliminar todas la variables
        header("Location: ".SERVER_URL."login/");
        exit();
    }