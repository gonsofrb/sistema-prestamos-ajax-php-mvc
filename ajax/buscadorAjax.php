<?php
     session_start(['name'=>'SPM']);
     require_once '../config/APP.php';

     if(isset($_POST['busqueda_inicial']) || isset($_POST['eliminar_busqueda']) || isset($_POST['busqueda_inicio_prestamo']) || isset($_POST['busqueda_final_prestamo'])){

        //url de las vistas
        $data_url=[
            "usuario"=>"user-search",
            "cliente"=>"client-search",
            "item"=>"item-search",
            "prestamo"=>"reservation-search"
        ];

        if(isset($_POST['modulo'])){
            $modulo=$_POST['modulo'];
            //Comprobamos si $modulo(usuario) está definido dentro del array $data_url.
            if(!isset($data_url[$modulo])){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No podemos continuar con la busqueda debido a un error.",
                    "Tipo"=>"error"
                ];
                    
                echo json_encode($alerta);
                exit();
            }
        }else{
            $alerta=[
                "Alerta"=>"simple",
                "Titulo"=>"Ocurrió un error inesperado",
                "Texto"=>"No podemos continuar con la busqueda debido a un error de configuración.",
                "Tipo"=>"error"
            ];
                
            echo json_encode($alerta);
            exit();
        }

        if($modulo=="prestamo"){
            $fecha_inicio="fecha_inicio_".$modulo;
            $fecha_final="fecha_final_".$modulo;

            //Iniciar busqueda
            if(isset($_POST['busqueda_inicio_prestamo']) || isset($_POST['busqueda_final_prestamo'])){
                if(empty($_POST['busqueda_inicio_prestamo']) || empty($_POST['busqueda_final_prestamo'])){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"Por favor introduce una fecha de inicio y una fecha final para continuar con la busqueda.",
                        "Tipo"=>"error"
                    ];
                        
                    echo json_encode($alerta);
                    exit();
                }
                    //Definimos las 2 variables de sesion
                $_SESSION[$fecha_inicio]=$_POST['busqueda_inicio_prestamo'];
                $_SESSION[$fecha_final]=$_POST['busqueda_final_prestamo'];

            }

            //Eliminar busqueda
            if(isset($_POST['eliminar_busqueda'])){
                unset($_SESSION[ $fecha_inicio]);
                unset($_SESSION[ $fecha_final]);

            }

        }else{
            //contendrá el nombre de los demás módulos
            $name_var="busqueda_".$modulo;

            //Iniciar busqueda
            if(isset($_POST['busqueda_inicial'])){
                if(empty($_POST['busqueda_inicial'])){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"Por favor introduce un termino de busqueda para empezar.",
                        "Tipo"=>"error"
                    ];
                        
                    echo json_encode($alerta);
                    exit();
                }
                $_SESSION[$name_var]=$_POST['busqueda_inicial'];
               

            }

            //Eliminar busqueda
            if(isset($_POST['eliminar_busqueda'])){
                unset( $_SESSION[$name_var]);

            }
        }

        //Redireccionar
        $url=$data_url[$modulo];
      
        $alerta=[
            "Alerta"=>"redireccionar",
            "URL"=>SERVER_URL.$url."/"

        ];
        echo json_encode($alerta);

     }else{
        session_unset();//vaciamos la sesión
        session_destroy();//destruimos la sesión para eliminar todas la variables
        header("Location: ".SERVER_URL."login/");
        exit();
     }