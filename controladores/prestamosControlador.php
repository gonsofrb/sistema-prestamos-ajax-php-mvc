<?php

    if($peticionAjax){
        require_once '../modelos/prestamoModelo.php';
    }else{
        require_once 'modelos/prestamoModelo.php';
    }

    class prestamosControlador extends prestamoModelo{

        /****Controlador buscar cliente prestamo****/
        public function buscar_cliente_prestamos_controlador(){
            /**** Recuperar texto*/ 
            $cliente=mainModelo::limpiar_cadena($_POST['buscar_cliente']);

            /**** Comprobar texto*/
            if(empty($cliente)){
                        return '<div class="alert alert-warning" role="alert">
                                <p class="text-center mb-0">
                                    <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                                    Debes introducir el DNI,NOMBRE,APELLIDOS Y TELEFONO.
                                </p>
                                </div>';
                        exit();
            }
            
            /**Seleccionando clientes en la bd*/
            $datos_cliente=mainModelo::ejecutar_consulta_simple("SELECT * FROM cliente WHERE cliente_dni LIKE '%$cliente%' OR cliente_nombre LIKE '%$cliente%' OR cliente_apellido LIKE '%$cliente%' OR cliente_telefono LIKE '%$cliente%' ORDER BY cliente_nombre ASC");

            if($datos_cliente->rowCount()>=1){
                $datos_cliente=$datos_cliente->fetchAll();

                $tabla='<div class="table-responsive"><table class="table table-hover table-bordered table-sm">
                    <tbody>';

                    foreach($datos_cliente as $rows){
                        $tabla.='<tr class="text-center">
                                    <td>'.$rows['cliente_nombre'].' '.$rows['cliente_apellido'].' - '.$rows['cliente_dni'].'</td>
                                    <td>
                                        <button type="button" class="btn btn-primary" onclick="agregar_cliente('.$rows['cliente_id'].');"><i
                                                class="fas fa-user-plus"></i></button>
                                    </td>
                                </tr>';
                    }
                $tabla.='</tbody>
                        </table>
                        </div>';
                return $tabla;            
            }else{
             
                        return '<div class="alert alert-warning" role="alert">
                                <p class="text-center mb-0">
                                    <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                                    No hemos encontrado ningún cliente en el sistema que coincida con <strong>“'.$cliente.'”</strong>
                                </p>
                                </div>';
                        exit();
            }
        }


         /****Controlador agregar cliente prestamo****/
        public function agregar_cliente_prestamos_controlador(){
             /**** Recuperar id_cliente*/ 
             $id=mainModelo::limpiar_cadena($_POST['id_agregar_cliente']);

             /**Comprobando el cliente en la bd */
             $check_cliente=mainModelo::ejecutar_consulta_simple("SELECT * FROM cliente WHERE cliente_id='$id'");
                if($check_cliente->rowCount()<=0){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"No hemos podido encontrar el cliente en la base de datos.",
                        "Tipo"=>"error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }else{
                    $campos=$check_cliente->fetch();
                }

                /****Iniciando la sesion*/
                session_start(['name'=>'SPM']);
                if(empty($_SESSION['datos_cliente'])){
                    $_SESSION['datos_cliente'] =[
                        "id"=>$campos['cliente_id'],
                        "dni"=>$campos['cliente_dni'],
                        "nombre"=>$campos['cliente_nombre'],
                        "apellido"=>$campos['cliente_apellido']
                    ];

                    $alerta=[
                        "Alerta"=>"recargar",
                        "Titulo"=>"Cliente agregado.",
                        "Texto"=>"EL CLIENTE se agregó para realizar un préstamo o reservacion.",
                        "Tipo"=>'success'
                    ];
                    echo json_encode($alerta);

                }else{
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"No hemos podido agregar el cliente al prestamo.",
                        "Tipo"=>'error'
                    ];
                    echo json_encode($alerta);
                } 
        }

         /****Controlador eliminar cliente prestamo****/
        public function eliminar_cliente_prestamos_controlador(){

            /**Iniciando la sesion*/
            session_start(['name'=>'SPM']);

            unset($_SESSION['datos_cliente']);

            if(empty($_SESSION['datos_cliente'])){
                $alerta=[
                    "Alerta"=>"recargar",
                    "Titulo"=>"Cliente eliminado",
                    "Texto"=>"Los datos del cliente se han eliminado con exito.",
                    "Tipo"=>'success'
                ];
            }else{
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No hemos podido eliminar los datos del cliente.",
                    "Tipo"=>'error'
                ];
            }
            echo json_encode($alerta);
        }

        /****Controlador buscar item prestamo****/
        public function buscar_item_prestamos_controlador(){
            /**** Recuperar texto*/ 
            $item=mainModelo::limpiar_cadena($_POST['buscar_item']);

            /**** Comprobar texto*/
            if(empty($item)){
                        return '<div class="alert alert-warning" role="alert">
                                <p class="text-center mb-0">
                                    <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                                    Debes introducir el codigo o nombre del item.
                                </p>
                                </div>';
                        exit();
            }
            
            /**Seleccionando item en la bd*/
            $datos_item=mainModelo::ejecutar_consulta_simple("SELECT * FROM item WHERE (item_codigo LIKE '%$item%' OR item_nombre LIKE '%$item%') AND (item_estado='Habilitado')  ORDER BY item_nombre ASC");

            if($datos_item->rowCount()>=1){
                $datos_item=$datos_item->fetchAll();

                $tabla='<div class="table-responsive"><table class="table table-hover table-bordered table-sm">
                    <tbody>';

                    foreach($datos_item as $rows){
                        $tabla.='<tr class="text-center">
                                    <td>'.$rows['item_codigo'].'-'.$rows['item_nombre'].'</td>
                                    <td>
                                        <button type="button" class="btn btn-primary" onclick="modal_agregar_item('.$rows['item_id'].')"><i
                                        class="fas fa-box-open"></i></button>
                                    </td>
                                </tr>';
                    }
                $tabla.='</tbody>
                        </table>
                        </div>';
                return $tabla;            
            }else{
             
                        return '<div class="alert alert-warning" role="alert">
                                <p class="text-center mb-0">
                                    <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                                    No hemos encontrado ningún item en el sistema que coincida con <strong>“'.$item.'”</strong>
                                </p>
                                </div>';
                        exit();
            }
        }

        /****Controlador agregar item prestamo****/
        public function agregar_item_prestamos_controlador(){

            /**** Recuperar id item*/ 
            $id=mainModelo::limpiar_cadena($_POST['id_agregar_item']);

            /**Comprobando el item en la bd */
            $check_item=mainModelo::ejecutar_consulta_simple("SELECT * FROM item WHERE item_id='$id' AND  item_estado='Habilitado'");

                if($check_item->rowCount()<=0){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"No hemos podido seleccionar el item, por favor intente nuevamente.",
                        "Tipo"=>"error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }else{
                    $campos=$check_item->fetch();
                }


                $formato=mainModelo::limpiar_cadena($_POST['detalle_formato']);
                $cantidad=mainModelo::limpiar_cadena($_POST['detalle_cantidad']);
                $tiempo=mainModelo::limpiar_cadena($_POST['detalle_tiempo']);
                $costo=mainModelo::limpiar_cadena($_POST['detalle_costo_tiempo']);


                /**-------comprobar campos vacios----------- */
                if(empty($cantidad) || empty($tiempo) || empty($costo)){
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
                    
                if(mainModelo::verificar_datos("[0-9]{1,7}",$cantidad)){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"LA CANTIDAD no coincide con el formato solicitado",
                        "Tipo"=>"error"
                    ];
                        //pasamos el array $alerta a json para javascript
                    echo json_encode($alerta);
                    exit();
                }

                if(mainModelo::verificar_datos("[0-9]{1,7}",$tiempo)){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"EL TIEMPO no coincide con el formato solicitado",
                        "Tipo"=>"error"
                    ];
                        //pasamos el array $alerta a json para javascript
                    echo json_encode($alerta);
                    exit();
                }

                if(mainModelo::verificar_datos("[0-9.]{1,15}",$costo)){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"EL COSTO no coincide con el formato solicitado",
                        "Tipo"=>"error"
                    ];
                        //pasamos el array $alerta a json para javascript
                    echo json_encode($alerta);
                    exit();
                }

                if($formato!="Horas" && $formato!="Dias" && $formato!="Evento" && $formato!="Mes"){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"EL formato no es válido.",
                        "Tipo"=>"error"
                    ];
                        //pasamos el array $alerta a json para javascript
                    echo json_encode($alerta);
                    exit();
                }

                //Iniciamos la sesion
                session_start(['name'=>'SPM']);
                if(empty($_SESSION['datos_item'][$id])){
                    //number_format($costo,decimal,separador,separador millar)
                    $costo=number_format($costo,2,'.','');

                    $_SESSION['datos_item'][$id]=[
                        "id"=>$campos['item_id'],
                        "codigo"=>$campos['item_codigo'],
                        "nombre"=>$campos['item_nombre'],
                        "detalle"=>$campos['item_detalle'],
                        "formato"=>$formato,
                        "cantidad"=>$cantidad,
                        "tiempo"=>$tiempo,
                        "costo"=>$costo
                    ];
                    $alerta=[
                        "Alerta"=>"recargar",
                        "Titulo"=>"Item agregado",
                        "Texto"=>"EL item ha sido agregado para realizar un préstamo.",
                        "Tipo"=>"success"
                    ];
                        
                    echo json_encode($alerta);
                    exit();

                    // $_SESSION['datos_item'] = [
                    //     3=>[
                    //         "id"=>"3",
                    //         "codigo"=>"001",
                    //         "nombre"=>"Mesa metalica",
                    //         "detalle"=>"Mesa metalica de color negro",
                    //         "formato"=>"dias",
                    //         "tiempo"=>"5",
                    //         "costo"=>"2.00"
                    //     ],
                    //     4=>[
                    //         "id"=>"4",
                    //         "codigo"=>"002",
                    //         "nombre"=>"Silla metalica",
                    //         "detalle"=>"Silla metalica de color negro",
                    //         "formato"=>"dias",
                    //         "tiempo"=>"1",
                    //         "costo"=>"3.00"
                    //     ]
                    // ];

                }else{
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"EL item que intenta agregar ya se encuentra seleccionado.",
                        "Tipo"=>"error"
                    ];
                        
                    echo json_encode($alerta);
                    exit();
                }

        }

        /****Controlador eliminar item prestamo****/
        public function eliminar_item_prestamos_controlador(){

            /**** Recuperar id*/ 
            $id=mainModelo::limpiar_cadena($_POST['id_eliminar_item']);

             /**Iniciando la sesion*/
             session_start(['name'=>'SPM']);

             unset($_SESSION['datos_item'][$id]);

             if(empty($_SESSION['datos_item'][$id])){
                 $alerta=[
                     "Alerta"=>"recargar",
                     "Titulo"=>"Item eliminado",
                     "Texto"=>"Los datos del item se han eliminado con exito.",
                     "Tipo"=>'success'
                 ];
             }else{
                 $alerta=[
                     "Alerta"=>"simple",
                     "Titulo"=>"Ocurrió un error inesperado",
                     "Texto"=>"No hemos podido eliminar los datos del Item.",
                     "Tipo"=>'error'
                 ];
             }
             echo json_encode($alerta);
        }

         /****Controlador datos prestamo****/
         public function datos_prestamo_controlador($tipo,$id){
            $id=mainModelo::limpiar_cadena($tipo);

            $id=mainModelo::descryption($id);
            $id=mainModelo::limpiar_cadena($id);

            return prestamoModelo::datos_prestamo_modelo($tipo,$id);
         }

        /****Controlador agregar prestamo****/
         public function agregar_prestamo_controlador(){

            /****Iniciando la sesion****/
            session_start(['name'=>'SPM']);

            /****Comprobando items****/
            if($_SESSION['prestamo_item']==0){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No has seleccionado ningún item para realizar el prestamo.",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }

            /****Comprobando cliente****/
            if(empty($_SESSION['datos_cliente'])){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No has selccionado ningún cliente para realizar el prestamo.",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }

            /****Recibiendo datos del formulario****/
            $fecha_inicio=mainModelo::limpiar_cadena($_POST['prestamo_fecha_inicio_reg']);
            $hora_inicio=mainModelo::limpiar_cadena($_POST['prestamo_hora_inicio_reg']);
            $fecha_final=mainModelo::limpiar_cadena($_POST['prestamo_fecha_final_reg']);
            $hora_final=mainModelo::limpiar_cadena($_POST['prestamo_hora_final_reg']);
            $estado=mainModelo::limpiar_cadena($_POST['prestamo_estado_reg']);
            $total_pagado=mainModelo::limpiar_cadena($_POST['prestamo_pagado_reg']);
            $observacion=mainModelo::limpiar_cadena($_POST['prestamo_observacion_reg']);

            /****Comprobando integridad de datos****/
            if(mainModelo::verificar_fecha($fecha_inicio)){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"La fecha de inicio no coincide con el formato solicitado",
                    "Tipo"=>"error"
                ];
                    //pasamos el array $alerta a json para javascript
                echo json_encode($alerta);
                exit();
            }

            if(mainModelo::verificar_datos("([0-1][0-9]|[2][0-3])[\:]([0-5][0-9])",$hora_inicio)){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"La hora de inicio no coincide con el formato solicitado",
                    "Tipo"=>"error"
                ];
                    //pasamos el array $alerta a json para javascript
                echo json_encode($alerta);
                exit();
            }

            if(mainModelo::verificar_fecha($fecha_final)){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"La fecha de entrega no coincide con el formato solicitado",
                    "Tipo"=>"error"
                ];
                    //pasamos el array $alerta a json para javascript
                echo json_encode($alerta);
                exit();
            }

            if(mainModelo::verificar_datos("([0-1][0-9]|[2][0-3])[\:]([0-5][0-9])",$hora_final)){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"La hora de entrega no coincide con el formato solicitado",
                    "Tipo"=>"error"
                ];
                    //pasamos el array $alerta a json para javascript
                echo json_encode($alerta);
                exit();
            }

            if(mainModelo::verificar_datos("[0-9.]{1,10}",$total_pagado)){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El total depositado no coincide con el formato solicitado",
                    "Tipo"=>"error"
                ];
                    //pasamos el array $alerta a json para javascript
                echo json_encode($alerta);
                exit();
            }

            if($observacion!=""){
                if(mainModelo::verificar_datos("[a-zA-z0-9áéíóúÁÉÍÓÚñÑ#() ]{1,400}",$observacion)){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"La observacion no coincide con el formato solicitado",
                        "Tipo"=>"error"
                    ];
                        //pasamos el array $alerta a json para javascript
                    echo json_encode($alerta);
                    exit();
                }
            }

            if($estado!="Reservacion" && $estado!="Prestamo" && $estado!="Finalizado"){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El estado no coincide con el formato solicitado",
                    "Tipo"=>"error"
                ];
                    //pasamos el array $alerta a json para javascript
                echo json_encode($alerta);
                exit();
            }


             /****Comprobando las fechas****/
             if(strtotime($fecha_final) < strtotime($fecha_inicio)){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"La fecha de entrega no puede ser menor que la fecha de inicio del prestamo.",
                    "Tipo"=>"error"
                ];
                    //pasamos el array $alerta a json para javascript
                echo json_encode($alerta);
                exit();
             }


             /****Formateando totales, fechas y horas****/
             $total_prestamo=number_format($_SESSION['prestamo_total'],2,'.','');
             $total_pagado=number_format($total_pagado,2,'.','');

             $fecha_inicio=date("Y-m-d",strtotime($fecha_inicio));
             $fecha_final=date("Y-m-d",strtotime($fecha_final));

             $hora_inicio=date("h:i a",strtotime($hora_inicio));
             $hora_final=date("h:i a",strtotime($hora_final));


             /****Generando código de prestamo****/
             $correlativo=mainModelo::ejecutar_consulta_simple("SELECT prestamo_id FROM prestamo");
             $correlativo=($correlativo->rowCount())+1;

             $codigo=mainModelo::generar_codigo_aleatorio("CP",7,$correlativo);

             $datos_prestamo_reg=[
                 "codigo"=>$codigo,
                 "fecha_inicio"=>$fecha_inicio,
                 "hora_inicio"=>$hora_inicio,
                 "fecha_final"=>$fecha_final,
                 "hora_final"=>$hora_final,
                 "cantidad"=>$_SESSION['prestamo_item'],
                 "total"=>$total_prestamo,
                 "pagado"=>$total_pagado,
                 "estado"=>$estado,
                 "observacion"=>$observacion,
                 "usuario_id"=>$_SESSION['id_spm'],
                 "cliente_id"=>$_SESSION['datos_cliente']['id']
             ];

               /****Agregar prestamo****/
               $agregar_prestamo=prestamoModelo::agregar_prestamo_modelo($datos_prestamo_reg);

               if($agregar_prestamo->rowCount()!=1){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado (Error:001)",
                    "Texto"=>"No hemos podido registrar el prestamo, por favor intente nuevamente.",
                    "Tipo"=>"error"
                ];
                    //pasamos el array $alerta a json para javascript
                echo json_encode($alerta);
                exit();
               }

               /****Agregar pago****/
               if($total_pagado>0){

                $datos_pago_reg=[
                    "total"=>$total_pagado,
                    "fecha"=>$fecha_inicio,
                    "codigo"=>$codigo

                ];

                    $agregar_pago=prestamoModelo::agregar_pago_modelo($datos_pago_reg);

                    //Al entrar en la condicion se tiene que eliminar el prestamo
                    if($agregar_pago->rowCount()!=1){
                        pretamoModelo::eliminar_prestamo_modelo($codigo,"Prestamo");

                        $alerta=[
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrió un error inesperado (Error:002)",
                            "Texto"=>"No hemos podido registrar el prestamo, por favor intente nuevamente.",
                            "Tipo"=>"error"
                        ];
                            //pasamos el array $alerta a json para javascript
                        echo json_encode($alerta);
                        exit();
                    }
               }

               /****Agregar detalle****/
               $errores_detalle=0;

               foreach($_SESSION['datos_item'] as $items){

                    $costo=number_format($items['costo'],2,'.','');
                    $descripcion=$items['codigo']."-".$items['nombre'];

                    $datos_detalle_reg=[
                        "cantidad"=>$items['cantidad'],
                        "formato"=>$items['formato'],
                        "tiempo"=>$items['tiempo'],
                        "costo"=>$costo,
                        "descripcion"=>$descripcion,
                        "prestamo"=>$codigo,
                        "item"=>$items['id']
                    ];

                    $agregar_detalle=prestamoModelo::agregar_detalle_modelo($datos_detalle_reg);
                        if($agregar_detalle->rowCount()!=1){
                            $errores_detalle=1;
                            break;
                        }

               }

               if($errores_detalle==0){
                   unset($_SESSION['datos_cliente']);
                   unset($_SESSION['datos_item']);
                $alerta=[
                    "Alerta"=>"recargar",
                    "Titulo"=>"Préstamo registrado",
                    "Texto"=>"Los datos del préstamo han sido registrados en el sistema.",
                    "Tipo"=>"success"
                ];
                    //pasamos el array $alerta a json para javascript
               
               }else{
                    pretamoModelo::eliminar_prestamo_modelo($codigo,"Detalle");
                    pretamoModelo::eliminar_prestamo_modelo($codigo,"Pago");
                    pretamoModelo::eliminar_prestamo_modelo($codigo,"Prestamo");

                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado (Error:003)",
                        "Texto"=>"No hemos podido registrar el prestamo, por favor intente nuevamente.",
                        "Tipo"=>"error"
                    ];
                        
                  
                    
               }
               echo json_encode($alerta);
            
         }

         /****Controlador mostrar prestamos****/
        //!AL BUSCAR POR SILABAS, MUESTRA TODO EL LISTADO. EJEMPLO BUSQUEDA A,S,D,h,l,e,
        public function mostrar_prestamos_controlador($pagina_actual,$n_registros,$privilegio,$url,$tipo,$fecha_inicio,$fecha_final){
            $pagina_actual=mainModelo::limpiar_cadena($pagina_actual);
            $n_registros=mainModelo::limpiar_cadena($n_registros);
            $privilegio=mainModelo::limpiar_cadena($privilegio);

            $url=mainModelo::limpiar_cadena($url);
            $url=SERVER_URL.$url."/";

            $tipo=mainModelo::limpiar_cadena($tipo);
            $fecha_inicio=mainModelo::limpiar_cadena($fecha_inicio);
            $fecha_final=mainModelo::limpiar_cadena($fecha_final);
            $tabla="";

                //Si $pagina_actual no viene definida o no es un numero ...será 1
            $pagina= (isset($pagina_actual) && $pagina_actual>0) ? (int)$pagina_actual : 1 ;
            $inicio= ($pagina>0) ? (($pagina*$n_registros)-$n_registros) : 0 ;

            if($tipo=="Busqueda"){
                if(mainModelo::verificar_fecha($fecha_inicio) || mainModelo::verificar_fecha($fecha_final)){
                    return '
                    <div class="alert alert-danger text-center" role="alert">
                        <p><i class="fas fa-exclamation-triangle fa-5x"></i></p>
                        <h4 class="alert-heading">¡Ocurrió un error inesperado!</h4>
                        <p class="mb-0">Lo sentimos, no podemos realizar la busqueda ya que ha ingresado una fecha incorrecta.</p>
                    </div>
                    ';
                    exit();
                }
            }

            $campos="prestamo.prestamo_id,prestamo.prestamo_codigo,prestamo.prestamo_fecha_inicio,prestamo.prestamo_fecha_final,prestamo.prestamo_total,prestamo.prestamo_pagado,prestamo.prestamo_estado,prestamo.usuario_id,prestamo.cliente_id,cliente.cliente_nombre,cliente.cliente_apellido";


            if($tipo=="Busqueda" && $fecha_inicio!="" && $fecha_final!=""){
                
                $consulta="SELECT SQL_CALC_FOUND_ROWS $campos FROM prestamo INNER JOIN cliente ON prestamo.cliente_id=cliente.cliente_id WHERE (prestamo.prestamo_fecha_inicio BETWEEN '$fecha_inicio' AND '$fecha_final') ORDER BY prestamo.prestamo_fecha_inicio DESC LIMIT $inicio,$n_registros";
              
            }else{
                        //Listas préstamos por estados
                $consulta="SELECT SQL_CALC_FOUND_ROWS $campos FROM prestamo INNER JOIN cliente ON prestamo.cliente_id=cliente.cliente_id WHERE prestamo.prestamo_estado='$tipo' ORDER BY prestamo.prestamo_fecha_inicio DESC LIMIT $inicio,$n_registros";
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
                            <th>CLIENTE</th>
                            <th>FECHA DE PRÉSTAMO</th>
                            <th>FECHA DE ENTREGA</th>
                            <th>TIPO</th>
                            <th>ESTADO</th>
                            <th>FACTURA</th>';
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

                    //Formateamos las fechas
                    foreach($datos as $row){

                            $tabla.=
                            '<tr class="text-center">
                            <td>'.$contador.'</td>
                            <td>'.$row['cliente_nombre'].' '.$row['cliente_apellido'].'</td>
                            <td>'.date("d-m-Y",strtotime($row['prestamo_fecha_inicio'])).'</td>
                            <td>'.date("d-m-Y",strtotime($row['prestamo_fecha_final'])).'</td>
                            <td>'.$row['prestamo_estado'].'</td>';

                            if($row['prestamo_pagado']<$row['prestamo_total']){

                                $tabla.='<td>Pendiente:<span class="badge badge-danger">'.MONEDA.number_format(($row['prestamo_total']-$row['prestamo_pagado']),2,'.','').'</span></td>';
                            }else{

                                $tabla.='<td><span class="badge badge-light">Cancelado</span></td>';
                            }

                            $tabla.='
                                <td>
                                    <a href="'.SERVER_URL.'facturas/invoice.php?id='.mainModelo::encryption($row['prestamo_id']).'" class="btn btn-info" target="_blank">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                </td>
                            ';

                            if($privilegio==1 || $privilegio == 2){

                                if($row['prestamo_estado']=="Finalizado" && $row['prestamo_pagado']==$row['prestamo_total'] ){

                                                $tabla.='<td>
                                                            <button class="btn btn-success" disabled>
                                                                <i class="fas fa-sync-alt"></i>
                                                            </button>
                                                        </td>';
                                }else{
                                    $tabla.='<td>
                                                <a href="'.SERVER_URL.'reservation-update/'.mainModelo::encryption($row['prestamo_id']).'/" class="btn btn-success">
                                                    <i class="fas fa-sync-alt"></i>
                                                </a>
                                            </td>';
                                }
                                
                                
                            }
                            if($privilegio==1){
                                $tabla.='<td>
                                <form class="FormularioAjax" action="'.SERVER_URL.'ajax/prestamoAjax.php" method="POST" data-form="delete" autocomplete="off">
                                <input type="hidden" name="prestamo_codigo_del" value="'.mainModelo::encryption($row['prestamo_codigo']).'">
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
                $tabla.='<p class="text-right">Mostrando prestamos '.$reg_inicio.' al '.$reg_final.' de un total de ' .$total.'</p>';
                $tabla.=mainModelo::paginador_tablas($pagina,$Npaginas,$url,7);
            }
            
            return $tabla;

        }

    }