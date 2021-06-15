<?php

    if($peticionAjax){
        require_once '../modelos/itemModelo.php';
    }else{
        require_once 'modelos/itemModelo.php';
    }

    class itemControlador extends itemModelo{

        /**Agregar item */
        public function agregar_item_controlador(){
            $codigo=mainModelo::limpiar_cadena($_POST['item_codigo_reg']);
            $nombre=mainModelo::limpiar_cadena($_POST['item_nombre_reg']);
            $stock=mainModelo::limpiar_cadena($_POST['item_stock_reg']);
            $estado=mainModelo::limpiar_cadena($_POST['item_estado_reg']);
            $detalle=mainModelo::limpiar_cadena($_POST['item_detalle_reg']);

              /**-------comprobar campos vacios----------- */
              if(empty($codigo) || empty($nombre) || empty($stock) || empty($estado)){        
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
            
                if(mainModelo::verificar_datos("[a-zA-Z0-9-]{1,45}",$codigo)){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"El CODIGO no coincide con el formato solicitado",
                        "Tipo"=>"error"
                    ];
                        //pasamos el array $alerta a json para javascript
                    echo json_encode($alerta);
                    exit();
                }
                
                if(mainModelo::verificar_datos("[a-zA-záéíóúÁÉÍÓÚñÑ0-9 ]{1,140}",$nombre)){
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
                
                if(mainModelo::verificar_datos("[0-9]{1,9}",$stock)){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"El STOCK no coincide con el formato solicitado",
                        "Tipo"=>"error"
                    ];
                        //pasamos el array $alerta a json para javascript
                    echo json_encode($alerta);
                    exit();
                }

                if(!empty($detalle)){
                    if(mainModelo::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}",$detalle)){
                        $alerta=[
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrió un error inesperado",
                            "Texto"=>"El DETALLE no coincide con el formato solicitado",
                            "Tipo"=>"error"
                        ];
                            //pasamos el array $alerta a json para javascript
                        echo json_encode($alerta);
                        exit();
                    }
                }
                     //!EXPLICACION DE LA VALIDACIÓN
                if($estado !="Habilitado" && $estado !="Deshabilitado"){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"El ESTADO del item no coincide con el formato solicitado",
                        "Tipo"=>"error"
                    ];
                        //pasamos el array $alerta a json para javascript
                    echo json_encode($alerta);
                    exit();
                }

                 /**-------Comprobar codigo----------- */
                 $check_codigo=mainModelo::ejecutar_consulta_simple("SELECT item_codigo FROM item WHERE item_codigo = '$codigo'");

                 if($check_codigo->rowCount()>0){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"El codigo que ha ingresado ya exite en el sistema.",
                        "Tipo"=>"error"
                    ];
                        //pasamos el array $alerta a json para javascript
                    echo json_encode($alerta);
                    exit();
                 }

                 /**-------Comprobar nombre----------- */
                 $check_nombre=mainModelo::ejecutar_consulta_simple("SELECT item_nombre FROM item WHERE item_nombre = '$nombre'");

                 if($check_nombre->rowCount()>0){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"El nombre del item que ha ingresado ya exite en el sistema.",
                        "Tipo"=>"error"
                    ];
                        //pasamos el array $alerta a json para javascript
                    echo json_encode($alerta);
                    exit();
                 }

                 $datos_item_reg = [
                     "codigo"=>$codigo,
                     "nombre"=>$nombre,
                     "stock"=>$stock,
                     "estado"=>$estado,
                     "detalle"=>$detalle

                 ];
                  
                 $agregar_item=itemModelo::agregar_item_modelo($datos_item_reg);
                 if($agregar_item->rowCount()==1){
                    $alerta=[
                        "Alerta"=>"limpiar",
                        "Titulo"=>"Item registrado",
                        "Texto"=>"Los datos del item han sido registrados con exito.",
                        "Tipo"=>"success"
                    ];
                 }else{
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"No hemos podido registrar el item, por favor intente nuevamente.",
                        "Tipo"=>"error"
                    ];
                 }
                 echo json_encode($alerta);


        }

        /**Mostrar los item */
        //!AL BUSCAR POR SILABAS, MUESTRA TODO EL LISTADO. EJEMPLO BUSQUEDA A,S,D,h,l,e,
        public function mostrar_item_controlador($pagina_actual,$n_registros,$privilegio,$url,$busqueda){
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
                
                $consulta="SELECT SQL_CALC_FOUND_ROWS * FROM item WHERE item_codigo LIKE '%$busqueda%' OR item_nombre LIKE '%$busqueda%' OR item_estado LIKE '%$busqueda%' ORDER BY item_nombre ASC LIMIT $inicio,$n_registros";
              
            }else{
                        //Listado normal de item---
                $consulta="SELECT SQL_CALC_FOUND_ROWS * FROM item ORDER BY item_nombre ASC LIMIT $inicio,$n_registros";
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
                            <th>CODIGO</th>
                            <th>NOMBRE</th>
                            <th>STOCK</th>
                            <th>ESTADO</th>
                            <th>DETALLE</th>';
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
                            <td>'.$row['item_codigo'].'</td>
                            <td>'.$row['item_nombre'].'</td>
                            <td>'.$row['item_stock'].'</td>
                            <td>'.$row['item_estado'].'</td>
                            <td><button type="button" class="btn btn-info" data-toggle="popover" data-trigger="hover" title="'.$row['item_nombre'].'" data-content="'.$row['item_detalle'].'"><i class="fas fa-info-circle"></i></button></td>';
                            if($privilegio==1 || $privilegio == 2){
                                $tabla.='<td>
                                <a href="'.SERVER_URL.'item-update/'.mainModelo::encryption($row['item_id']).'/" class="btn btn-success">
                                    <i class="fas fa-sync-alt"></i>
                                </a>
                            </td>';
                                
                            }
                            if($privilegio==1){
                                $tabla.='<td>
                                <form class="FormularioAjax" action="'.SERVER_URL.'ajax/itemAjax.php" method="POST" data-form="delete" autocomplete="off">
                                <input type="hidden" name="item_id_del" value="'.mainModelo::encryption($row['item_id']).'">
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
                        $tabla.='<tr class="text-center"><td colspan="8">
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
                $tabla.='<p class="text-right">Mostrando item '.$reg_inicio.' al '.$reg_final.' de un total de ' .$total.'</p>';
                $tabla.=mainModelo::paginador_tablas($pagina,$Npaginas,$url,7);
            }
            
            return $tabla;

        }

         

       
        /**----Eliminar  item */
        public function eliminar_item_controlador(){
            //Recibiendo el id
            $id=mainModelo::descryption($_POST['item_id_del']);
            $id=mainModelo::limpiar_cadena($id);

            //Comprobar item esté en la bd
            $check_item=mainModelo::ejecutar_consulta_simple("SELECT item_id FROM item WHERE item_id='$id'");
                if($check_item->rowCount()<=0){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"El ITEM que intenta eliminar no exite en el sistema.",
                        "Tipo"=>"error"
                    ];
                       
                    echo json_encode($alerta);
                    exit();
                    }
            
            //Comprobar detalles de prestamo
            $check_prestamos=mainModelo::ejecutar_consulta_simple("SELECT item_id FROM detalle WHERE item_id='$id' LIMIT 1");
                if($check_prestamos->rowCount()>0){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"El ITEM que intenta eliminar tiene prestamos asociados, recomendamos deshabilitar el item si ya no sera usado en el sistema.",
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
            
            $eliminar_item=itemModelo::eliminar_item_modelo($id);
                if($eliminar_item->rowCount()==1){
                    $alerta=[
                        "Alerta"=>"recargar",
                        "Titulo"=>"Item eliminado",
                        "Texto"=>"El item ha sido eliminado del sistema con exito.",
                        "Tipo"=>"success"
                    ];
                }else{
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"No hemos podido eliminar el item del sistema, por favor intente nuevamente.",
                        "Tipo"=>"error"
                    ];
                }
                echo json_encode($alerta);
        }



         /*-------Controlador datos item--------*/
         public function datos_item_controlador($tipo,$id){
            $tipo=mainModelo::limpiar_cadena($tipo);

            $id=mainModelo::descryption($id);
            $id=mainModelo::limpiar_cadena($id);

            return itemModelo::datos_item_modelo($tipo,$id);

        }


        
        /*-------Controlador actualizar item--------*/
        public function actualizar_item_controlador(){
             //Recuperar el id del item---
             $id=mainModelo::descryption($_POST['item_id_up']);
             $id=mainModelo::limpiar_cadena($id);

             //Comprobar el item en la BD---
             $check_item=mainModelo::ejecutar_consulta_simple("SELECT * FROM item WHERE item_id='$id'");

             if($check_item->rowCount()<=0){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No hemos encontrado el item en el sistema.",
                    "Tipo"=>"error"
                ];
                    //pasamos el array $alerta a json para javascript
                echo json_encode($alerta);
                exit();
             }else{
                $campos=$check_item->fetch();
             }
             
             $codigo=mainModelo::limpiar_cadena($_POST['item_codigo_up']);
             $nombre=mainModelo::limpiar_cadena($_POST['item_nombre_up']);
             $stock=mainModelo::limpiar_cadena($_POST['item_stock_up']);
             $estado=mainModelo::limpiar_cadena($_POST['item_estado_up']);
             $detalle=mainModelo::limpiar_cadena($_POST['item_detalle_up']);

              /**-------comprobar campos vacios----------- */
              if(empty($codigo) || empty($nombre) || empty($stock) || empty($estado)){        
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
            
                if(mainModelo::verificar_datos("[a-zA-Z0-9-]{1,45}",$codigo)){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"El CODIGO no coincide con el formato solicitado",
                        "Tipo"=>"error"
                    ];
                        //pasamos el array $alerta a json para javascript
                    echo json_encode($alerta);
                    exit();
                }
                
                if(mainModelo::verificar_datos("[a-zA-záéíóúÁÉÍÓÚñÑ0-9 ]{1,140}",$nombre)){
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
                
                if(mainModelo::verificar_datos("[0-9]{1,9}",$stock)){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"El STOCK no coincide con el formato solicitado",
                        "Tipo"=>"error"
                    ];
                        //pasamos el array $alerta a json para javascript
                    echo json_encode($alerta);
                    exit();
                }

                if(!empty($detalle)){
                    if(mainModelo::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}",$detalle)){
                        $alerta=[
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrió un error inesperado",
                            "Texto"=>"El DETALLE no coincide con el formato solicitado",
                            "Tipo"=>"error"
                        ];
                            //pasamos el array $alerta a json para javascript
                        echo json_encode($alerta);
                        exit();
                    }
                }
                     //!EXPLICACION DE LA VALIDACIÓN
                if($estado !="Habilitado" && $estado !="Deshabilitado"){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"El ESTADO del item no coincide con el formato solicitado",
                        "Tipo"=>"error"
                    ];
                        //pasamos el array $alerta a json para javascript
                    echo json_encode($alerta);
                    exit();
                }

                 /**-------Comprobar codigo----------- */
                 if($codigo!=$campos['item_codigo']){
                            $check_codigo=mainModelo::ejecutar_consulta_simple("SELECT item_codigo FROM item WHERE item_codigo = '$codigo'");

                        if($check_codigo->rowCount()>0){
                            $alerta=[
                                "Alerta"=>"simple",
                                "Titulo"=>"Ocurrió un error inesperado",
                                "Texto"=>"El codigo que ha ingresado ya exite en el sistema.",
                                "Tipo"=>"error"
                            ];
                                //pasamos el array $alerta a json para javascript
                            echo json_encode($alerta);
                            exit();
                        }

                 }
                 
                 /**-------Comprobar nombre----------- */
                 if($nombre!=$campos['item_nombre']){
                    $check_nombre=mainModelo::ejecutar_consulta_simple("SELECT item_nombre FROM item WHERE item_nombre = '$nombre'");

                    if($check_nombre->rowCount()>0){
                       $alerta=[
                           "Alerta"=>"simple",
                           "Titulo"=>"Ocurrió un error inesperado",
                           "Texto"=>"El nombre del item que ha ingresado ya exite en el sistema.",
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


                  $datos_item_up = [
                    "codigo"=>$codigo,
                    "nombre"=>$nombre,
                    "stock"=>$stock,
                    "estado"=>$estado,
                    "detalle"=>$detalle,
                    "id"=>$id

                ];

                if(itemModelo::actualizar_item_modelo($datos_item_up)){
                    $alerta=[
                        "Alerta"=>"recargar",
                        "Titulo"=>"Item actualizado.",
                        "Texto"=>"Los datos del item han sido actualizados con exito.",
                        "Tipo"=>"success"
                    ];
                }else{
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"No hemos podido actualizar los datos del item, por favor intente nuevamente.",
                        "Tipo"=>"error"
                    ];
                }
                echo json_encode($alerta);
                 

        }

        
    }
