<?php

    if($peticionAjax){
        require_once '../modelos/empresaModelo.php';
    }else{
        require_once 'modelos/empresaModelo.php';
    }

    class empresaControlador extends empresaModelo{

        /**Controlador datos empresa */
        public function datos_empresa_controlador(){
            return empresaModelo::datos_empresa_modelo();
    
        }

         /**Controlador agregar empresa */
         public function agregar_empresa_controlador(){
            $nombre=mainModelo::limpiar_cadena($_POST['empresa_nombre_reg']);
            $email=mainModelo::limpiar_cadena($_POST['empresa_email_reg']);
            $telefono=mainModelo::limpiar_cadena($_POST['empresa_telefono_reg']);
            $direccion=mainModelo::limpiar_cadena($_POST['empresa_direccion_reg']);


                  
            /**-------comprobar campos vacios----------- */
            if(empty($nombre) || empty($email) || empty($telefono) || empty($direccion)){        
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No has llenado todos los campos que son obligatorios",
                    "Tipo"=>"error"
                ];
                    //pasamos el array $alerta a json para javascript
                echo json_encode($alerta);
                exit();
            
                    }


                             
                    /**-------Verificar integridad de los datos----------- */
                
                    if(mainModelo::verificar_datos("[a-zA-z0-9áéíóúÁÉÍÓÚñÑ. ]{1,70}",$nombre)){
                        $alerta=[
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrió un error inesperado",
                            "Texto"=>"El NOMBRE no coincide con el formato solicitado",
                            "Tipo"=>"error"
                        ];
                            //pasamos el array $alerta a json para javascript
                        echo json_encode($alerta);
                        exit();
                    }
                    
                    
                    if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
                        $alerta=[
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrió un error inesperado",
                            "Texto"=>"El EMAIL no coincide con el formato solicitado",
                            "Tipo"=>"error"
                        ];
                            //pasamos el array $alerta a json para javascript
                        echo json_encode($alerta);
                        exit();
                    }
                    
                    if(mainModelo::verificar_datos("[0-9()+]{8,20}",$telefono)){
                        $alerta=[
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrió un error inesperado",
                            "Texto"=>"El TELEFONO no coincide con el formato solicitado",
                            "Tipo"=>"error"
                        ];
                            //pasamos el array $alerta a json para javascript
                        echo json_encode($alerta);
                        exit();
                    }

                    if(mainModelo::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}",$direccion)){
                        $alerta=[
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrió un error inesperado",
                            "Texto"=>"LA DIRECCION no coincide con el formato solicitado",
                            "Tipo"=>"error"
                        ];
                            //pasamos el array $alerta a json para javascript
                        echo json_encode($alerta);
                        exit();
                    }

                     /**-------Comprobar empresas registradas----------- */
                     $check_empresas=mainModelo::ejecutar_consulta_simple("SELECT empresa_id FROM empresa");

                     if($check_empresas->rowCount()>=1){
                        $alerta=[
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrió un error inesperado",
                            "Texto"=>"YA EXISTE una empresa registrada, ya no puedes registrar mas.",
                            "Tipo"=>"error"
                        ];
                            //pasamos el array $alerta a json para javascript
                        echo json_encode($alerta);
                        exit();
                     }

                     $datos_empresa_reg=[
                         "nombre"=>$nombre,
                         "email"=>$email,
                         "telefono"=>$telefono,
                         "direccion"=>$direccion
                     ];

                     $agregar_empresa=empresaModelo::agregar_empresa_modelo($datos_empresa_reg);

                     if($agregar_empresa->rowCount()==1){
                        $alerta=[
                            "Alerta"=>"recargar",
                            "Titulo"=>"Empresa registrada",
                            "Texto"=>"Los datos de la empresa se registraron con exito.",
                            "Tipo"=>"success"
                        ];
                     }else{
                        $alerta=[
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrió un error inesperado",
                            "Texto"=>"NO hemos podido registrar la empresa, por favor intente nuevamente.",
                            "Tipo"=>"error"
                        ];
                     }
                     echo json_encode($alerta);

         } 
         
          /**Controlador actualizar empresa */
         public function actualizar_empresa_controlador(){
             $id=mainModelo::limpiar_cadena($_POST['empresa_id_up']);
             $nombre=mainModelo::limpiar_cadena($_POST['empresa_nombre_up']);
             $email=mainModelo::limpiar_cadena($_POST['empresa_email_up']);
             $telefono=mainModelo::limpiar_cadena($_POST['empresa_telefono_up']);
             $direccion=mainModelo::limpiar_cadena($_POST['empresa_direccion_up']);

            
                    
            /**-------comprobar campos vacios----------- */
            if(empty($nombre) || empty($email) || empty($telefono) || empty($direccion)){        
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No has llenado todos los campos que son obligatorios",
                    "Tipo"=>"error"
                ];
                    //pasamos el array $alerta a json para javascript
                echo json_encode($alerta);
                exit();
            
                    }


                     /**-------Verificar integridad de los datos----------- */
                
                     if(mainModelo::verificar_datos("[a-zA-z0-9áéíóúÁÉÍÓÚñÑ. ]{1,70}",$nombre)){
                        $alerta=[
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrió un error inesperado",
                            "Texto"=>"El NOMBRE no coincide con el formato solicitado",
                            "Tipo"=>"error"
                        ];
                            //pasamos el array $alerta a json para javascript
                        echo json_encode($alerta);
                        exit();
                    }
                    
                    
                    if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
                        $alerta=[
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrió un error inesperado",
                            "Texto"=>"El EMAIL no coincide con el formato solicitado",
                            "Tipo"=>"error"
                        ];
                            //pasamos el array $alerta a json para javascript
                        echo json_encode($alerta);
                        exit();
                    }
                    
                    if(mainModelo::verificar_datos("[0-9()+]{8,20}",$telefono)){
                        $alerta=[
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrió un error inesperado",
                            "Texto"=>"El TELEFONO no coincide con el formato solicitado",
                            "Tipo"=>"error"
                        ];
                            //pasamos el array $alerta a json para javascript
                        echo json_encode($alerta);
                        exit();
                    }

                    if(mainModelo::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}",$direccion)){
                        $alerta=[
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrió un error inesperado",
                            "Texto"=>"LA DIRECCION no coincide con el formato solicitado",
                            "Tipo"=>"error"
                        ];
                            //pasamos el array $alerta a json para javascript
                        echo json_encode($alerta);
                        exit();
                    }

                    /**-------comprobar privilegios----------- */
                    session_start(['name'=>'SPM']);
                    if($_SESSION['privilegio_spm'] <1 || $_SESSION['privilegio_spm']>2){
                        $alerta=[
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrió un error inesperado",
                            "Texto"=>"No tienes los permisos necesarios para realizar esta operacion.",
                            "Tipo"=>"error"
                        ];
                            //pasamos el array $alerta a json para javascript
                        echo json_encode($alerta);
                        exit();
                    }

                    $datos_empresa_up=[
                        "id"=>$id,
                        "nombre"=>$nombre,
                        "email"=>$email,
                        "telefono"=>$telefono,
                        "direccion"=>$direccion
                    ];

                    if(empresaModelo::actualizar_empresa_modelo($datos_empresa_up)){
                        $alerta=[
                            "Alerta"=>"recargar",
                            "Titulo"=>"Empresa actualizada",
                            "Texto"=>"Los datos de la empresa han sido actualizados con exito .",
                            "Tipo"=>"success"
                        ];
                    }else{
                        $alerta=[
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrió un error inesperado",
                            "Texto"=>"No hemos podido actualizar los datos de la empresa, por favor intente nuevamente.",
                            "Tipo"=>"error"
                        ];
                    }
                    echo json_encode($alerta);





         }

    }/**Fin controlador */
