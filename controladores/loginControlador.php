<?php

    if($peticionAjax){
        require_once '../modelos/loginModelo.php';
    }else{
        require_once 'modelos/loginModelo.php';
    }

    class loginControlador extends loginModelo{

        /**----Controlador iniciar sesion */
        public function iniciar_sesion_controlador(){
            $usuario=mainModelo::limpiar_cadena(ucwords($_POST['usuario_log']));
            $clave=mainModelo::limpiar_cadena($_POST['clave_log']);

            // var_dump($usuario);
            // var_dump($clave);
           // die();


             /**----Comprobar campos vacios*/
             if(empty($usuario) || empty($clave)){
                 echo '<script>
                    Swal.fire({
                        title:"Ocurrio un error inesperado",
                        text: "No has rellenado todos los campos que son requeridos",
                        type: "error",
                        confirmButtonText: "Aceptar"
                    });
                  
                    </script>';
                    exit();
                   
             }

             /**-------Verificar integridad de los datos----------- */
             if(mainModelo::verificar_datos("[a-zA-Z0-9]{1,35}",$usuario)){
                    echo '<script>
                    Swal.fire({
                        title:"Ocurrio un error inesperado",
                        text: "El NOMBRE DE USUARIO no coincide con el formato solicitado",
                        type: "error",
                        confirmButtonText: "Aceptar"
                    });
                
                    </script>';
                    exit();
                  
                }

             if(mainModelo::verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave)){
                echo '<script>
                Swal.fire({
                    title:"Ocurrio un error inesperado",
                    text: "LA CLAVE  no coincide con el formato solicitado",
                    type: "error",
                    confirmButtonText: "Aceptar"
                });
            
                </script>';
                exit();
             
                }


                // $clave=mainModelo::encryption($clave);

              
                $datos_login=[
                    "usuario"=>$usuario
                    // "clave"=>$clave

                ];
     
                   
                   
                $datos_cuenta=loginModelo::iniciar_sesion_modelo($datos_login);
              
                if($datos_cuenta->rowCount()==1){
                   $row=$datos_cuenta->fetch();

                    $contrasena=$row['usuario_clave'];
                    // var_dump($contrasena);
                    // die();
                   //verificamos la contraseña
                   $verificar = password_verify($clave,$contrasena);
                //    var_dump($clave);
                //    var_dump($contrasena);
                //    var_dump($verificar);
                //    die();

                        if($verificar){

                            session_start(['name'=>'SPM']);

                            $_SESSION['id_spm']=$row['usuario_id'];
                            $_SESSION['nombre_spm']=$row['usuario_nombre'];
                            $_SESSION['apellido_spm']=$row['usuario_apellido'];
                            $_SESSION['usuario_spm']=$row['usuario_usuario'];
                            $_SESSION['privilegio_spm']=$row['usuario_privilegio'];
                            $_SESSION['token_spm']=md5(uniqid(mt_rand(),true));//Se procesa por md5 un numero unico para cada sesion


                                return header("Location: ".SERVER_URL."home/");

                        }else{
                           
                            echo '<script>
                            Swal.fire({
                                title:"Ocurrio un error inesperado",
                                text: "EL USUARIO O CLAVE son incorrectos",
                                type: "error",
                                confirmButtonText: "Aceptar"
                            });
                        
                            </script>';
                        }

                           

                }else{
                  
                    echo '<script>
                    Swal.fire({
                        title:"Ocurrio un error inesperado",
                        text: "EL USUARIO O CLAVE son incorrectos",
                        type: "error",
                        confirmButtonText: "Aceptar"
                    });
                
                    </script>';
                }
        }

        /**Forzar cierre  sesion */
        public function forzar_cierre_sesion_controlador(){
            session_unset();
            session_destroy();

            //Si estamos enviando encabezados por php no podemos enviar el header("Location:"), se envia mediante javascript
            if(headers_sent()){
                return "<script>window.location.href='".SERVER_URL."login/';</script>";
            }else{

                return header("Location: ".SERVER_URL."login/");
            }
        }

        /** Cerrar  Sesion */
        public function cerrar_sesion(){
            session_start(['name'=>'SPM']);
            $token = mainModelo::descryption($_POST['token']);
            $usuario = mainModelo::descryption($_POST['usuario']);

            //Comprobar si esos dos valores son identicos a los que estan almacenados en la variable de sesion
            if($token == $_SESSION['token_spm'] && $usuario==$_SESSION['usuario_spm']){
                session_unset();
                session_destroy();
                //Hacemos redireccion por la funcion alertas_ajax
                $alerta=[
                    "Alerta"=>"redireccionar",
                    "URL"=>SERVER_URL."login/"
                ];
            }else{
                //Si hay un error
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No se pude cerrar la sesion en el sistema",
                    "Tipo"=>"error"
                ];

            }
            echo json_encode($alerta);
        }


    }/**Fin controlador */