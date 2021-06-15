<?php

    if($peticionAjax){
        require_once '../modelos/usuarioModelo.php';
    }else{
        require_once 'modelos/usuarioModelo.php';
    }

    class usuarioControlador extends usuarioModelo{

        /*-------Controlador agregar usuario--------*/
        public  function agregar_usuario_controlador(){
            $dni=mainModelo::limpiar_cadena($_POST['usuario_dni_reg']);
            $nombre=mainModelo::limpiar_cadena(ucwords($_POST['usuario_nombre_reg']));
            $apellido=mainModelo::limpiar_cadena(ucwords($_POST['usuario_apellido_reg']));
            $telefono=mainModelo::limpiar_cadena($_POST['usuario_telefono_reg']);
            $direccion=mainModelo::limpiar_cadena($_POST['usuario_direccion_reg']);

            $usuario=mainModelo::limpiar_cadena(ucwords($_POST['usuario_usuario_reg']));
            $email=mainModelo::limpiar_cadena($_POST['usuario_email_reg']);
            $contrasena1=mainModelo::limpiar_cadena($_POST['usuario_clave_1_reg']);
            $contrasena2=mainModelo::limpiar_cadena($_POST['usuario_clave_2_reg']);

            $privilegio=mainModelo::limpiar_cadena($_POST['usuario_privilegio_reg']);

                 
            /**-------comprobar campos vacios----------- */
            if(empty($dni) || empty($nombre) || empty($apellido) || empty($usuario) || empty($contrasena1) || empty($contrasena2) || empty($email)){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No has llenado todos los campos que son obligatorios",
                    "Tipo"=>"error"
                ];
                    //pasamos el array $alerta a json para javascript
                echo json_encode($alerta);
                exit();

            }else{
                                      
                                    
                    /**-------Verificar integridad de los datos----------- */
                
                    if(mainModelo::verificar_datos("(\d{8})([-]?)([A-Za-z]{1})",$dni)){
                        $alerta=[
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrió un error inesperado",
                            "Texto"=>"El DNI no coincide con el formato solicitado",
                            "Tipo"=>"error"
                        ];
                            //pasamos el array $alerta a json para javascript
                        echo json_encode($alerta);
                        exit();
                    }

                    if(mainModelo::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,10}",$nombre)){
                        $alerta=[
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrió un error inesperado",
                            "Texto"=>"El nombre no coincide con el formato solicitado",
                            "Tipo"=>"error"
                        ];
                            //pasamos el array $alerta a json para javascript
                        echo json_encode($alerta);
                        exit();
                    }

                    if(mainModelo::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,10}",$apellido)){
                        $alerta=[
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrió un error inesperado",
                            "Texto"=>"El apellido no coincide con el formato solicitado",
                            "Tipo"=>"error"
                        ];
                            //pasamos el array $alerta a json para javascript
                        echo json_encode($alerta);
                        exit();
                    }

                    //Si trae texto entra en la condición
                    if($telefono!=""){
                        if(mainModelo::verificar_datos("[0-9()+]{8,20}",$telefono)){
                            $alerta=[
                                "Alerta"=>"simple",
                                "Titulo"=>"Ocurrió un error inesperado",
                                "Texto"=>"El telefono no coincide con el formato solicitado",
                                "Tipo"=>"error"
                            ];
                                //pasamos el array $alerta a json para javascript
                            echo json_encode($alerta);
                            exit();
                        }
                    }

                    if($direccion!=""){
                        if(mainModelo::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}",$direccion)){
                            $alerta=[
                                "Alerta"=>"simple",
                                "Titulo"=>"Ocurrió un error inesperado",
                                "Texto"=>"La direccion no coincide con el formato solicitado",
                                "Tipo"=>"error"
                            ];
                                //pasamos el array $alerta a json para javascript
                            echo json_encode($alerta);
                            exit();
                        }
                    }


                    if(mainModelo::verificar_datos("[a-zA-Z0-9]{1,35}",$usuario)){
                        $alerta=[
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrió un error inesperado",
                            "Texto"=>"El nombre de usuario no coincide con el formato solicitado",
                            "Tipo"=>"error"
                        ];
                            //pasamos el array $alerta a json para javascript
                        echo json_encode($alerta);
                        exit();
                    }

                       
                    if(mainModelo::verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$contrasena1) || mainModelo::verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$contrasena2)){
                        $alerta=[
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrió un error inesperado",
                            "Texto"=>"Las claves no coincide con el formato solicitado",
                            "Tipo"=>"error"
                        ];
                            //pasamos el array $alerta a json para javascript
                        echo json_encode($alerta);
                        exit();
                    }

                     /**-------Comprobacion claves----------- */
                     if($contrasena1!=$contrasena2){
                        $alerta=[
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrió un error inesperado",
                            "Texto"=>"Las claves que ha ingresado no coinciden",
                            "Tipo"=>"error"
                        ];
                            //pasamos el array $alerta a json para javascript
                        echo json_encode($alerta);
                        exit();
                    }else{
                            
                        $clave_segura=password_hash($contrasena1,PASSWORD_BCRYPT,['cost'=>4]);
                       

                    }

                    

                    //  /**-------Comprobación DNI que no este registrado ya en la bd----------- */
                    $check_dni=mainModelo::ejecutar_consulta_simple("SELECT usuario_dni FROM usuario WHERE usuario_dni = '$dni'");
                    if($check_dni->rowCount()>0){
                        $alerta=[
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrión un error inesperado",
                            "Texto"=>"El dni ingresado ya se encuentra registrado en el sistema",
                            "Tipo"=>"error"
                        ];
                            //pasamos el array $alerta a json para javascript
                        echo json_encode($alerta);
                        exit();
                    }


                    
                    /**-------Comprobación usuario que no este registrado ya en la bd----------- */
                    $check_usuario=mainModelo::ejecutar_consulta_simple("SELECT usuario_usuario FROM usuario WHERE usuario_usuario = '$usuario'");
                    if($check_usuario->rowCount()>0){
                        $alerta=[
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrió un error inesperado",
                            "Texto"=>"El nombre de usuario ingresado ya se encuentra registrado en el sistema",
                            "Tipo"=>"error"
                        ];
                            //pasamos el array $alerta a json para javascript
                        echo json_encode($alerta);
                        exit();
                    }

                    /**-------!Comprobación email que no este registrado ya en la bd----------- *///!Vulnerabilidad de datos, se ofrece información innecesaria al comprobar que el email ya está registrado.
                    if($email!=""){
                            if(filter_var($email,FILTER_VALIDATE_EMAIL)){
                                $check_email=mainModelo::ejecutar_consulta_simple("SELECT usuario_email FROM usuario WHERE usuario_email = '$email'");
                                if($check_email->rowCount()>0){
                                    $alerta=[
                                        "Alerta"=>"simple",
                                        "Titulo"=>"Ocurrió un error inesperado",
                                        "Texto"=>"El email ingresado ya se encuentra registrado en el sistema",
                                        "Tipo"=>"error"
                                    ];
                                        //pasamos el array $alerta a json para javascript
                                    echo json_encode($alerta);
                                    exit();
                                    }

                            }else{
                                $alerta=[
                                    "Alerta"=>"simple",
                                    "Titulo"=>"Ocurrió un error inesperado",
                                    "Texto"=>"Ha ingresado un email no valido",
                                    "Tipo"=>"error"
                                ];
                                    //pasamos el array $alerta a json para javascript
                                echo json_encode($alerta);
                                exit();
                            }
                    }

                   
                
                    /**-------Comprobacion privilegio----------- */
                    if($privilegio<1 || $privilegio>3){
                        $alerta=[
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrió un error inesperado",
                            "Texto"=>"Debe seleccionar un privilegio correcto",
                            "Tipo"=>"error"
                        ];
                            //pasamos el array $alerta a json para javascript
                        echo json_encode($alerta);
                        exit();
                    }

                    //Datos del formulario en un array
                    $datos_usuario_reg=[
                        "dni"=>$dni,
                        "nombre"=>$nombre,
                        "apellido"=>$apellido,
                        "telefono"=>$telefono,
                        "direccion"=>$direccion,
                        "email"=>$email,
                        "usuario"=>$usuario,
                        "clave"=>$clave_segura,
                        "estado"=>"Activa",
                        "privilegio"=>$privilegio

                    ];
                    
                    //Llamada al método del modelo
                    $agregar_usuario=usuarioModelo::agregar_usuario_modelo($datos_usuario_reg);

                    if($agregar_usuario->rowCount()==1){
                        $alerta=[
                            "Alerta"=>"limpiar",
                            "Titulo"=>"Usuario registrado",
                            "Texto"=>"Los datos del usuario se han registrado con exito",
                            "Tipo"=>"success"
                        ];
                            
                    
                    }else{
                        $alerta=[
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrió un error inesperado",
                            "Texto"=>"No se ha podido registrar el usuario",
                            "Tipo"=>"error"
                        ];
                            
                    }
                    echo json_encode($alerta);

                 }
        }

        /**Mostrar todos los usuarios */
        //$privilegio lo utilizamos para ocultar permiso de actualizar y eliminar
        //$id del usuario que inicia sesion
        //$url la vista para crear los enlaces de los botones
        //$busqueda para reutilizar el código, listar y buscar usuarios
        public function mostrar_usuarios_controlador($pagina_actual,$n_registros,$privilegio,$id,$url,$busqueda){
            $pagina_actual=mainModelo::limpiar_cadena($pagina_actual);
            $n_registros=mainModelo::limpiar_cadena($n_registros);
            $privilegio=mainModelo::limpiar_cadena($privilegio);
            $id=mainModelo::limpiar_cadena($id);

            $url=mainModelo::limpiar_cadena($url);
            $url=SERVER_URL.$url."/";

            $busqueda=mainModelo::limpiar_cadena($busqueda);

            $tabla="";

                //Si $pagina_actual no viene definida o no es un numero ...será 1
            $pagina= (isset($pagina_actual) && $pagina_actual>0) ? (int)$pagina_actual : 1 ;
            $inicio= ($pagina>0) ? (($pagina*$n_registros)-$n_registros) : 0 ;


            if(isset($busqueda) && !empty($busqueda)){
                
                $consulta="SELECT SQL_CALC_FOUND_ROWS * FROM usuario WHERE ((usuario_id!='$id' AND usuario_id!='7') AND (usuario_dni LIKE '%$busqueda%' OR usuario_nombre LIKE '%$busqueda%' OR usuario_apellido LIKE '%$busqueda%' OR usuario_email LIKE '%$busqueda%'))  ORDER BY usuario_nombre ASC LIMIT $inicio,$n_registros";
               
              
            }else{
                        //Listado normal de usuarios
                $consulta="SELECT SQL_CALC_FOUND_ROWS * FROM usuario WHERE usuario_id!='$id' AND usuario_id!=7 ORDER BY usuario_nombre ASC LIMIT $inicio,$n_registros";
            }

            $conexion=mainModelo::conectar();

            $datos = $conexion->query($consulta);

            $datos = $datos->fetchAll();

            $total=$conexion->query("SELECT FOUND_ROWS()");
            $total = (int)$total->fetchColumn();//Nos dice el total de columnas

            //ceil() para redondear el numero de paginas
            $Npaginas=ceil($total/$n_registros);

            $tabla.=' <div class="table-responsive">
                <table class="table table-dark table-sm">
                    <thead>
                        <tr class="text-center roboto-medium">
                            <th>#</th>
                            <th>DNI</th>
                            <th>NOMBRE</th>
                            <th>APELLIDO</th>
                            <th>TELÉFONO</th>
                            <th>USUARIO</th>
                            <th>EMAIL</th>
                            <th>ACTUALIZAR</th>
                            <th>ELIMINAR</th>
                        </tr>
                    </thead>
                    <tbody>';
                        //Comprobamos que exitan registros para mostrarlos
                if($total>=1 && $pagina<=$Npaginas){
                    //Mostramos los registros dentro de la tabla
                    $contador=$inicio+1;
                    $reg_inicio=$inicio+1;
                    foreach($datos as $row){

                            $tabla.=
                            '<tr class="text-center">
                            <td>'.$contador.'</td>
                            <td>'.$row['usuario_dni'].'</td>
                            <td>'.$row['usuario_nombre'].'</td>
                            <td>'.$row['usuario_apellido'].'</td>
                            <td>'.$row['usuario_telefono'].'</td>
                            <td>'.$row['usuario_usuario'].'</td>
                            <td>'.$row['usuario_email'].'</td>
                            <td>
                                <a href="'.SERVER_URL.'user-update/'.mainModelo::encryption($row['usuario_id']).'/" class="btn btn-success">
                                    <i class="fas fa-sync-alt"></i>
                                </a>
                            </td>
                            <td>
                                <form class="FormularioAjax" action="'.SERVER_URL.'ajax/usuarioAjax.php" method="POST" data-form="delete" autocomplete="off"">
                                    <input type="hidden" name="usuario_id_delete" value="'.mainModelo::encryption($row['usuario_id']).'">
                                    <button type="submit" class="btn btn-warning">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>';
                        $contador++;
                    }
                    $reg_final=$contador-1;

                }else{
                    if($total>=1){
                                //Si el usuario ingresa en una pagina que no existe pero si hay registro
                        $tabla.='<tr class="text-center"><td colspan="9">
                        <a href="'.$url.'" class="btn btn-raised btn-primary btn-sm">Haga click para recargar el listado</a>
                        </td></tr>';

                    }else{
                            //Si no hay registros
                        $tabla.='<tr class="text-center"><td colspan="9">No hay registros en el sistema</td></tr>';

                    }
                        
                }

            $tabla.='</tbody></table></div>';  
            
            // if($total>=1 && $pagina<=$Npaginas){
                
            // }

            //Agregar botones 
            if($total>=1 && $pagina<=$Npaginas){
                $tabla.='<p class="text-right">Mostrando usuario '.$reg_inicio.' al '.$reg_final.' de un total de ' .$total.'</p>';
                $tabla.=mainModelo::paginador_tablas($pagina,$Npaginas,$url,7);
            }
            
            return $tabla;

        }

        /**Eliminar un usuario */
        public function eliminar_usuario_controlador(){
            //Recibimos el id del usuario
            $id=mainModelo::descryption($_POST['usuario_id_delete']);
           
            $id=mainModelo::limpiar_cadena($id);

            //Comprobando el usuario administrador
            if($id==7){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No  podemos eliminar el usuario principal del sistema",
                    "Tipo"=>"error"
                ];
                    //pasamos el array $alerta a json para javascript
                echo json_encode($alerta);
                exit();
            }
            //Comprobando usuario en la BD
            $check_usuario=mainModelo::ejecutar_consulta_simple("SELECT usuario_id FROM usuario WHERE usuario_id='$id'");
            if($check_usuario->rowCount()<=0){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El usuario que intenta eliminar no existe en el sistema",
                    "Tipo"=>"error"
                ];
                   
                echo json_encode($alerta);
                exit();
            }

            //Comprobando los prestamos
            $check_prestamos=mainModelo::ejecutar_consulta_simple("SELECT usuario_id FROM prestamo WHERE usuario_id='$id' LIMIT 1");
            if($check_prestamos->rowCount()>0){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No podemos eliminar este usuario debido a que tiene prestamos asociados, recomendamos deshabilitar el usuario si ya no sera utilizado",
                    "Tipo"=>"error"
                ];
                   
                echo json_encode($alerta);
                exit();
            }

            //Comprobando privilegios
            session_start(['name'=>'SPM']);
            if($_SESSION['privilegio_spm']!=1){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No tienes los permisos necesarios para realizar esta operacion",
                    "Tipo"=>"error"
                ];
                   
                echo json_encode($alerta);
                exit();
            }
            $eliminar_usuario=usuarioModelo::eliminar_usuario_modelo($id);

            if($eliminar_usuario->rowCount()==1){
                $alerta=[
                    "Alerta"=>"recargar",
                    "Titulo"=>"Usuario eliminado",
                    "Texto"=>"El usuario ha sido eliminado del sistema correctamente",
                    "Tipo"=>"success"
                ];
                   
              
            }else{
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No hemos podido eliminar el usuario, por favor intente nuevamente",
                    "Tipo"=>"error"
                ];
                   
                
            }
            echo json_encode($alerta);
                exit();
        }

        /*-------Controlador datos usuario--------*/
        public function datos_usuario_controlador($tipo,$id){
            $tipo=mainModelo::limpiar_cadena($tipo);

            $id=mainModelo::descryption($id);
            $id=mainModelo::limpiar_cadena($id);

            return usuarioModelo::datos_usuario_modelo($tipo,$id);

        }
            
        /*-------Controlador actualizar usuario--------*/
        public function actualizar_usuario_controlador(){

            //Recibimos el id
            $id=mainModelo::descryption($_POST['usuario_id_up']);
            $id=mainModelo::limpiar_cadena($id);

            //Comprobamos que el id exista en la BD
            $check_user=mainModelo::ejecutar_consulta_simple("SELECT * FROM usuario WHERE usuario_id='$id'");

            if($check_user->rowCount()<=0){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No hemos encontrado el usuario en el sistema",
                    "Tipo"=>"error"
                ];
                    
                echo json_encode($alerta);
                exit();
            }else{
                $campos=$check_user->fetch();
            }

            $dni=mainModelo::limpiar_cadena($_POST['usuario_dni_up']);
            $nombre=mainModelo::limpiar_cadena(ucwords($_POST['usuario_nombre_up']));
            $apellido=mainModelo::limpiar_cadena(ucwords($_POST['usuario_apellido_up']));
            $telefono=mainModelo::limpiar_cadena($_POST['usuario_telefono_up']);
            $direccion=mainModelo::limpiar_cadena($_POST['usuario_direccion_up']);

            $usuario=mainModelo::limpiar_cadena(ucwords($_POST['usuario_usuario_up']));
            $email=mainModelo::limpiar_cadena($_POST['usuario_email_up']);

            //Si $estado viene definido
            if(isset($_POST['usuario_estado_up'])){
                $estado=mainModelo::limpiar_cadena($_POST['usuario_estado_up']);

                //Si $estado NO viene definido
            }else{
                $estado= $campos['usuario_estado'];
            }

             //Si $privilegio viene definido
             if(isset($_POST['usuario_privilegio_up'])){
                $privilegio=mainModelo::limpiar_cadena($_POST['usuario_privilegio_up']);

                //Si $privilegio NO viene definido
            }else{
                $privilegio= $campos['usuario_privilegio'];
            }

            $admin_usuario=mainModelo::limpiar_cadena(ucwords($_POST['usuario_admin']));

            $admin_clave=mainModelo::limpiar_cadena($_POST['clave_admin']);
           
           
            $tipo_cuenta=mainModelo::limpiar_cadena($_POST['tipo_cuenta']);


             /**-------comprobar campos vacios----------- */
             if(empty($dni) || empty($nombre) || empty($apellido) || empty($usuario) || empty($email) || empty($admin_usuario) || empty($admin_clave)){
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
                
             if(mainModelo::verificar_datos("(\d{8})([-]?)([A-Za-z]{1})",$dni)){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El DNI no coincide con el formato solicitado",
                    "Tipo"=>"error"
                ];
                    //pasamos el array $alerta a json para javascript
                echo json_encode($alerta);
                exit();
            }

            if(mainModelo::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,15}",$nombre)){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El nombre no coincide con el formato solicitado",
                    "Tipo"=>"error"
                ];
                    //pasamos el array $alerta a json para javascript
                echo json_encode($alerta);
                exit();
            }

            if(mainModelo::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,10}",$apellido)){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El apellido no coincide con el formato solicitado",
                    "Tipo"=>"error"
                ];
                    //pasamos el array $alerta a json para javascript
                echo json_encode($alerta);
                exit();
            }

            //Si trae texto entra en la condición
            if($telefono!=""){
                if(mainModelo::verificar_datos("[0-9()+]{8,20}",$telefono)){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"El telefono no coincide con el formato solicitado",
                        "Tipo"=>"error"
                    ];
                        //pasamos el array $alerta a json para javascript
                    echo json_encode($alerta);
                    exit();
                }
            }

            if($direccion!=""){
                if(mainModelo::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}",$direccion)){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"La direccion no coincide con el formato solicitado",
                        "Tipo"=>"error"
                    ];
                        //pasamos el array $alerta a json para javascript
                    echo json_encode($alerta);
                    exit();
                }
            }


            if(mainModelo::verificar_datos("[a-zA-Z0-9]{1,35}",$usuario)){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El nombre de usuario no coincide con el formato solicitado",
                    "Tipo"=>"error"
                ];
                    //pasamos el array $alerta a json para javascript
                echo json_encode($alerta);
                exit();
            }


            if(mainModelo::verificar_datos("[a-zA-Z0-9]{1,35}",$admin_usuario)){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"TU NOMBRE DE USUARIO no coincide con el formato solicitado",
                    "Tipo"=>"error"
                ];
                    //pasamos el array $alerta a json para javascript
                echo json_encode($alerta);
                exit();
            }

            if(mainModelo::verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$admin_clave)){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"TU CLAVE no coincide con el formato solicitado",
                    "Tipo"=>"error"
                ];
                    //pasamos el array $alerta a json para javascript
                echo json_encode($alerta);
                exit();
            }
            // $admin_clave_segura=password_hash($admin_clave,PASSWORD_BCRYPT,['cost'=>4]);
            
      
            
            //Comprobación de privilegio
            if($privilegio<1 || $privilegio>3){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"EL PRIVILEGIO seleccionado no es valido",
                    "Tipo"=>"error"
                ];
                    //pasamos el array $alerta a json para javascript
                echo json_encode($alerta);
                exit();
            }
            //Verificacion estado cuenta
            if($estado!="Activa" && $estado!="Deshabilitada"){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"EL ESTADO de la cuenta no coincide con el formato solicitado",
                    "Tipo"=>"error"
                ];
                    //pasamos el array $alerta a json para javascript
                echo json_encode($alerta);
                exit();
            }

             //  /**-------Comprobación DNI que no este registrado ya en la bd----------- */

             if($dni!=$campos['usuario_dni']){
                $check_dni=mainModelo::ejecutar_consulta_simple("SELECT usuario_dni FROM usuario WHERE usuario_dni = '$dni'");
                if($check_dni->rowCount()>0){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrión un error inesperado",
                        "Texto"=>"El dni ingresado ya se encuentra registrado en el sistema",
                        "Tipo"=>"error"
                    ];
                        //pasamos el array $alerta a json para javascript
                    echo json_encode($alerta);
                    exit();
                }
             }
             


             
             /**-------Comprobación usuario que no este registrado ya en la bd----------- */
             if($usuario!=$campos['usuario_usuario']){
                $check_usuario=mainModelo::ejecutar_consulta_simple("SELECT usuario_usuario FROM usuario WHERE usuario_usuario = '$usuario'");
                if($check_usuario->rowCount()>0){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"El nombre de usuario ingresado ya se encuentra registrado en el sistema",
                        "Tipo"=>"error"
                    ];
                        //pasamos el array $alerta a json para javascript
                    echo json_encode($alerta);
                    exit();
                }
             }

              /**-------!Comprobación email que no este registrado ya en la bd----------- *///!Vulnerabilidad de datos, se ofrece información innecesaria al comprobar que el email ya está registrado.
             
              if($email!=$campos['usuario_email']){
                if(filter_var($email,FILTER_VALIDATE_EMAIL)){
                    $check_email=mainModelo::ejecutar_consulta_simple("SELECT usuario_email FROM usuario WHERE usuario_email = '$email'");
                    if($check_email->rowCount()>0){
                        $alerta=[
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrió un error inesperado",
                            "Texto"=>"El email ingresado ya se encuentra registrado en el sistema",
                            "Tipo"=>"error"
                        ];
                            //pasamos el array $alerta a json para javascript
                        echo json_encode($alerta);
                        exit();
                        }

                }else{
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"Ha ingresado un email no valido",
                        "Tipo"=>"error"
                    ];
                        //pasamos el array $alerta a json para javascript
                    echo json_encode($alerta);
                    exit();
                }
        
              }
                

             
            

             
            /**-------Comprobación claves----------- */
            if(!empty($_POST['usuario_clave_nueva_1']) || !empty($_POST['usuario_clave_nueva_2'])){
                if($_POST['usuario_clave_nueva_1']!=$_POST['usuario_clave_nueva_2']){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"Las nuevas claves ingresadas no coinciden",
                        "Tipo"=>"error"
                    ];
                        //pasamos el array $alerta a json para javascript
                    echo json_encode($alerta);
                    exit();
                }else{
                    if(mainModelo::verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$_POST['usuario_clave_nueva_1']) || mainModelo::verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$_POST['usuario_clave_nueva_2'])){
                        $alerta=[
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrió un error inesperado",
                            "Texto"=>"Las nuevas claves ingresadas no coinciden con el formato solicitado",
                            "Tipo"=>"error"
                        ];
                            //pasamos el array $alerta a json para javascript
                        echo json_encode($alerta);
                        exit();
                    }

                    $clave=password_hash($_POST['usuario_clave_nueva_1'],PASSWORD_BCRYPT,['cost'=>4]);

                }
            }else{
                $clave=$campos['usuario_clave'];
            }

            /**-------Comprobación credenciales para actualizar datos----------- */
            
            if($tipo_cuenta=="Propia"){
                $check_cuenta=mainModelo::ejecutar_consulta_simple("SELECT usuario_id,usuario_clave FROM usuario WHERE usuario_usuario='$admin_usuario' AND usuario_id='$id'");
             

            }else{
              
                //No es cuenta propia y se comprueba si tiene permisos para realizar esa operacion
                session_start(['name'=>'SPM']);
                if($_SESSION['privilegio_spm']!=1){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"No tienes los permisos necesarios para realizar esta operacion",
                        "Tipo"=>"error"
                    ];
                        //pasamos el array $alerta a json para javascript
                    echo json_encode($alerta);
                    exit();
                }
                //Comprobacion si la cuenta existe
                $check_cuenta=mainModelo::ejecutar_consulta_simple("SELECT usuario_clave FROM usuario WHERE usuario_usuario='$admin_usuario'");
            
            }
           
            //contamos cuantos registros 
            if($check_cuenta->rowCount()==1){
                $row=$check_cuenta->fetch();
                $contrasena=$row['usuario_clave'];
            //    var_dump($admin_clave);
            //    echo '<br>';
            //    var_dump($contrasena);

                $verificar =password_verify($admin_clave,$contrasena);
                // var_dump($verificar);
                if($verificar != true){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"NOMBRE Y CLAVE DE ADMINISTRADOR no validos",
                        "Tipo"=>"error"
                    ];
                        //pasamos el array $alerta a json para javascript
                    echo json_encode($alerta);
                   
                    exit();
                }
               
            }else{
                
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"NOMBRE Y CLAVE DE ADMINISTRADOR no validos",
                    "Tipo"=>"error"
                ];
                    //pasamos el array $alerta a json para javascript
                echo json_encode($alerta);
              
                exit();
            }

            /**-------Preparando datos para enviarlos al modelo----------- */
            $datos_usuario_up=[
                'dni'=>$dni,
                'nombre'=>$nombre,
                'apellido'=>$apellido,
                'telefono'=>$telefono,
                'direccion'=>$direccion,
                'email'=>$email,
                'usuario'=>$usuario,
                'clave'=>$clave,
                'estado'=>$estado,
                'privilegio'=>$privilegio,
                'id'=>$id
            ];

            if(usuarioModelo::actualizar_usuario_modelo($datos_usuario_up)){
                $alerta=[
                    "Alerta"=>"recargar",
                    "Titulo"=>"Datos actualizados",
                    "Texto"=>"Los datos han sido actualizados con exito",
                    "Tipo"=>"success"
                ];

            }else{
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No hemos podido actualizar los datos, por favor intente nuevamente",
                    "Tipo"=>"error"
                ];
            }
            echo json_encode($alerta);


        }


    }/**Fin controlador */