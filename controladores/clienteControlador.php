<?php

    if($peticionAjax){
        require_once '../modelos/clienteModelo.php';
    }else{
        require_once 'modelos/clienteModelo.php';
    }

    class clienteControlador extends clienteModelo{

         /*-------Controlador agregar cliente--------*/
         public function agregar_cliente_controlador(){

             $dni=mainModelo::limpiar_cadena($_POST['cliente_dni_reg']);
             $nombre=mainModelo::limpiar_cadena($_POST['cliente_nombre_reg']);
             $apellido=mainModelo::limpiar_cadena($_POST['cliente_apellido_reg']);
             $telefono=mainModelo::limpiar_cadena($_POST['cliente_telefono_reg']);
             $direccion=mainModelo::limpiar_cadena($_POST['cliente_direccion_reg']);

             /**-------comprobar campos vacios----------- */
             if(empty($dni) || empty($nombre) || empty($apellido) || empty($telefono) || empty($direccion)){
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

                    if(mainModelo::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}",$nombre)){
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

                    
                    if(mainModelo::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}",$apellido)){
                        $alerta=[
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrió un error inesperado",
                            "Texto"=>"El APELLIDO no coincide con el formato solicitado",
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

                    if(mainModelo::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}",$direccion)){
                        $alerta=[
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrió un error inesperado",
                            "Texto"=>"LA DIRECCION  no coincide con el formato solicitado",
                            "Tipo"=>"error"
                        ];
                            //pasamos el array $alerta a json para javascript
                        echo json_encode($alerta);
                        exit();
                    }

                    /**-------Comprobar DNI  que no se repita en la BD----------- */
                    $check_dni=mainModelo::ejecutar_consulta_simple("SELECT cliente_dni FROM cliente WHERE cliente_dni='$dni'");

                    if($check_dni->rowCount()>0){
                        $alerta=[
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrió un error inesperado",
                            "Texto"=>"EL DNI ingresado ya se encuentra en el sistema.",
                            "Tipo"=>"error"
                        ];
                            //pasamos el array $alerta a json para javascript
                        echo json_encode($alerta);
                        exit();
                    }

                    $datos_cliente_reg=[
                        "dni"=>$dni,
                        "nombre"=>$nombre,
                        "apellido"=>$apellido,
                        "telefono"=>$telefono,
                        "direccion"=>$direccion
                    ];

                    $agregar_cliente=clienteModelo::agregar_cliente_modelo($datos_cliente_reg);

                    if($agregar_cliente->rowCount()==1){
                        $alerta=[
                            "Alerta"=>"limpiar",
                            "Titulo"=>"Cliente registrado.",
                            "Texto"=>"Los datos del cliente se registraron con exito.",
                            "Tipo"=>"success"
                        ];
                           
                    }else{
                        $alerta=[
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrió un error inesperado",
                            "Texto"=>"No hemos podido registrar el cliente, por favor intente nuevamente.",
                            "Tipo"=>"error"
                        ];
                            
                    }
                    echo json_encode($alerta);
        }

         /**Mostrar todos los clientes */
        //$privilegio lo utilizamos para ocultar permiso de actualizar y eliminar
        //$url la vista para crear los enlaces de los botones
        //$busqueda para reutilizar el código, listar y buscar clientes
        public function mostrar_clientes_controlador($pagina_actual,$n_registros,$privilegio,$id,$url,$busqueda){
            $pagina_actual=mainModelo::limpiar_cadena($pagina_actual);
            $n_registros=mainModelo::limpiar_cadena($n_registros);
            $privilegio=mainModelo::limpiar_cadena($privilegio);

            $url=mainModelo::limpiar_cadena($url);
            $url=SERVER_URL.$url."/";

            $busqueda=mainModelo::limpiar_cadena($busqueda);
            $tabla="";

                //Si $pagina_actual no viene definida o no es un numero ...será 1
            $pagina= (isset($pagina_actual) && $pagina_actual>0) ? (int)$pagina_actual : 1 ;
            $inicio= ($pagina>0) ? (($pagina*$n_registros)-$n_registros) : 0 ;


            if(isset($busqueda) && !empty($busqueda)){
                
                $consulta="SELECT SQL_CALC_FOUND_ROWS * FROM cliente WHERE cliente_dni LIKE '%$busqueda%' OR cliente_nombre LIKE '%$busqueda%' OR cliente_apellido LIKE '%$busqueda%' OR cliente_telefono LIKE '%$busqueda%'  ORDER BY cliente_nombre ASC LIMIT $inicio,$n_registros";
              
            }else{
                        //Listado normal de usuarios---
                $consulta="SELECT SQL_CALC_FOUND_ROWS * FROM cliente ORDER BY cliente_nombre ASC LIMIT $inicio,$n_registros";
            }

            $conexion=mainModelo::conectar();

            $datos = $conexion->query($consulta);

            $datos = $datos->fetchAll();

            $total=$conexion->query("SELECT FOUND_ROWS()");
            $total = (int)$total->fetchColumn();//Nos dice el total de columnas---

            //ceil() para redondear el numero de paginas---
            $Npaginas=ceil($total/$n_registros);

            $tabla.='<div class="table-responsive">
                <table class="table table-dark table-sm">
                    <thead>
                        <tr class="text-center roboto-medium">
                            <th>#</th>
                            <th>DNI</th>
                            <th>NOMBRE</th>
                            <th>APELLIDO</th>
                            <th>TELÉFONO</th>
                            <th>DIRECCION</th>';
                            if($privilegio==1 || $privilegio == 2){
                                $tabla.='<th>ACTUALIZAR</th>';
                            }
                            if($privilegio==1){
                                $tabla.='<th>ELIMINAR</th>';
                            }
                            $tabla.=' </tr>
                    </thead>
                    <tbody>';

                        //Comprobamos que exitan registros para mostrarlos---
                if($total>=1 && $pagina<=$Npaginas){
                    //Mostramos los registros dentro de la tabla---
                    $contador=$inicio+1;
                    $reg_inicio=$inicio+1;
                    foreach($datos as $row){

                            $tabla.=
                            '<tr class="text-center">
                            <td>'.$contador.'</td>
                            <td>'.$row['cliente_dni'].'</td>
                            <td>'.$row['cliente_nombre'].'</td>
                            <td>'.$row['cliente_apellido'].'</td>
                            <td>'.$row['cliente_telefono'].'</td>
                            <td><button type="button" class="btn btn-info" data-toggle="popover" data-trigger="hover" title="'.$row['cliente_nombre'].' '.$row['cliente_apellido'].'" data-content="'.$row['cliente_direccion'].'"><i class="fas fa-info-circle"></i></button></td>';
                            if($privilegio==1 || $privilegio == 2){
                                $tabla.='<td>
                                <a href="'.SERVER_URL.'client-update/'.mainModelo::encryption($row['cliente_id']).'/" class="btn btn-success">
                                    <i class="fas fa-sync-alt"></i>
                                </a>
                            </td>';
                                
                            }
                            if($privilegio==1){
                                $tabla.='<td>
                                <form class="FormularioAjax" action="'.SERVER_URL.'ajax/clienteAjax.php" method="POST" data-form="delete" autocomplete="off">
                                <input type="hidden" name="cliente_id_del" value="'.mainModelo::encryption($row['cliente_id']).'">
                                <button type="submit" class="btn btn-warning"><i class="far fa-trash-alt"></i></button>
                                </form>
                            </td>';
                            }
                            $tabla.='</tr>';
                        $contador++;
                    }
                    $reg_final=$contador-1;

                }else{
                    if($total>=1){
                                //Si el usuario ingresa en una pagina que no existe pero si hay registro---
                        $tabla.='<tr class="text-center"><td colspan="9">
                        <a href="'.$url.'" class="btn btn-raised btn-primary btn-sm">Haga click para recargar el listado</a>
                        </td></tr>';

                    }else{
                            //Si no hay registros---
                        $tabla.='<tr class="text-center"><td colspan="9">No hay registros en el sistema</td></tr>';

                    }
                        
                }

            $tabla.='</tbody></table></div>';  
            
            // if($total>=1 && $pagina<=$Npaginas){
                
            // }

            //Agregar botones--- 
            if($total>=1 && $pagina<=$Npaginas){
                $tabla.='<p class="text-right">Mostrando cliente '.$reg_inicio.' al '.$reg_final.' de un total de ' .$total.'</p>';
                $tabla.=mainModelo::paginador_tablas($pagina,$Npaginas,$url,7);
            }
            
            return $tabla;

        }

        /*-------Controlador eliminar cliente--------*/
        public function eliminar_cliente_controlador(){
            //Recibir el $id del cliente
            $id=mainModelo::descryption($_POST['cliente_id_del']);
            $id=mainModelo::limpiar_cadena($id);

            //Comprobar si el cliente existe en la BD
            $check_cliente=mainModelo::ejecutar_consulta_simple("SELECT cliente_id FROM cliente WHERE cliente_id='$id'");
            if($check_cliente->rowCount()<=0){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No hemos encontrado el cliente en el sistema.",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }

            //Comprobar prestamos
            $check_prestamos=mainModelo::ejecutar_consulta_simple("SELECT cliente_id FROM pretamos WHERE cliente_id='$id' LIMIT 1");
            if($check_prestamos->rowCount()>0){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No podemos eliminar el cliente del sistema porque tiene prestamos asociados.",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }

            //Comprobar los privilegios 
            session_start(['name'=>'SPM']);
            if($_SESSION['privilegio_spm']!=1){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"NO TIENES LOS PERMISOS necesarios para realizar esta operacion.",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }

            $eliminar_cliente=clienteModelo::eliminar_cliente_modelo($id);

            if($eliminar_cliente->rowCount()==1){
                $alerta=[
                    "Alerta"=>"recargar",
                    "Titulo"=>"Cliente eliminado",
                    "Texto"=>"EL CLIENTE ha sido eliminado del sistema con exito.",
                    "Tipo"=>"success"
                ];
            }else{
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"NO hemos podido eliminar el cliente, por favor intente nuevamente.",
                    "Tipo"=>"error"
                ];
            }
            echo json_encode($alerta);

        }

         /*-------Controlador datos cliente--------*/
         public function datos_cliente_controlador($tipo,$id){
             $tipo=mainModelo::limpiar_cadena($tipo);

             $id=mainModelo::descryption($id);
             $id=mainModelo::limpiar_cadena($id);

             return clienteModelo::datos_cliente_modelo($tipo,$id);

         }

         /*-------Controlador actualizar cliente--------*/
         public function actualizar_cliente_controlador(){
             //Recuperar el id del cliente---
             $id=mainModelo::descryption($_POST['cliente_id_up']);
             $id=mainModelo::limpiar_cadena($id);

             //Comprobar el cliente en la BD---
             $check_cliente=mainModelo::ejecutar_consulta_simple("SELECT * FROM cliente WHERE cliente_id='$id'");

             if($check_cliente->rowCount()<=0){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No hemos encontrado el cliente en el sistema.",
                    "Tipo"=>"error"
                ];
                    //pasamos el array $alerta a json para javascript
                echo json_encode($alerta);
                exit();
             }else{
                $campos=$check_cliente->fetch();
             }
             $dni=mainModelo::limpiar_cadena($_POST['cliente_dni_up']);
             $nombre=mainModelo::limpiar_cadena($_POST['cliente_nombre_up']);
             $apellido=mainModelo::limpiar_cadena($_POST['cliente_apellido_up']);
             $telefono=mainModelo::limpiar_cadena($_POST['cliente_telefono_up']);
             $direccion=mainModelo::limpiar_cadena($_POST['cliente_direccion_up']);

              /**-------comprobar campos vacios----------- */
              if(empty($dni) || empty($nombre) || empty($apellido) || empty($telefono) || empty($direccion)){
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

                    if(mainModelo::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}",$nombre)){
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

                    
                    if(mainModelo::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}",$apellido)){
                        $alerta=[
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrió un error inesperado",
                            "Texto"=>"El APELLIDO no coincide con el formato solicitado",
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

                    if(mainModelo::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}",$direccion)){
                        $alerta=[
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrió un error inesperado",
                            "Texto"=>"LA DIRECCION  no coincide con el formato solicitado",
                            "Tipo"=>"error"
                        ];
                            //pasamos el array $alerta a json para javascript
                        echo json_encode($alerta);
                        exit();
                    }

                    /**-------Comprobar DNI  que no se repita en la BD----------- */
                    if($dni!=$campos['cliente_dni']){
                        $check_dni=mainModelo::ejecutar_consulta_simple("SELECT cliente_dni FROM cliente WHERE cliente_dni='$dni'");

                        if($check_dni->rowCount()>0){
                            $alerta=[
                                "Alerta"=>"simple",
                                "Titulo"=>"Ocurrió un error inesperado",
                                "Texto"=>"EL DNI ingresado ya se encuentra en el sistema.",
                                "Tipo"=>"error"
                            ];
                                //pasamos el array $alerta a json para javascript
                            echo json_encode($alerta);
                            exit();
                        }
                    }

                    //Comprobar privilegios
                    session_start(['name'=>'SPM']);
                    if($_SESSION['privilegio_spm']<1 || $_SESSION['privilegio_spm']>2){
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


                    $datos_cliente_up=[
                        "dni"=>$dni,
                        "nombre"=>$nombre,
                        "apellido"=>$apellido,
                        "telefono"=>$telefono,
                        "direccion"=>$direccion,
                        "id"=>$id
                    ];
                   
                    if(clienteModelo::actualizar_cliente_modelo($datos_cliente_up)){
                        $alerta=[
                            "Alerta"=>"recargar",
                            "Titulo"=>"Cliente actualizado",
                            "Texto"=>"Los datos del cliente han sido actualizados con exito.",
                            "Tipo"=>"success"
                        ];
                    }else{
                        $alerta=[
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrió un error inesperado",
                            "Texto"=>"No hemos podido actualizar los datos del cliente, por favor intente nuevamente.",
                            "Tipo"=>"error"
                        ];
                    }
                    echo json_encode($alerta);


        }

    }
