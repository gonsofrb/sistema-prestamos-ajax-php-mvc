<?php

if($peticionAjax){
    //Dentro de la carpeta ajax
    require_once '../config/SERVER.php';
}else{
    //Desde el index.php
    require_once 'config/SERVER.php';
}

    class mainModel{

        /*-------Función conectar a la bd--------*/
        protected static function conectar(){
            $conexion = new PDO(SGBD,USER,PASS);
            $conexion->exec("SET CHARACTER SET utf8");
            return $conexion;
        } 

        /*-------Función ejecutar consultas simples--------*/
        protected static function ejecutar_consulta_simple($consulta){
            //self hace referencia en este caso a una funcion de la clase
            $sql=self::conectar()->prepare($consulta);
            $sql->execute();
            return $sql;
        }

        /*-------Encriptar cadenas--------*/
        public  function encryption($string){
            $output=FALSE;
            $key=has('sha256',SECRET_KEY);
            $iv=substr(hash('sha256',SECRET_IV), 0, 16);
            $output=openssl_encrypt($string, METHOD, $key, 0, $iv);
            $output=base64_encode($output);
            return $output;
        }
        /*-------Desencriptar cadenas--------*/
        protected static function descryption($string){
            $key=hash('sha256', SECRET_KEY);
            $iv=substr(hash('sha256', SECRET_IV), 0, 16);
            $output=openssl_decrypt(base64_decode($string), METHOD, $key, 0, $iv);
            return $output;

        }

        /*-------Función generar códigos aleatorios--------*/
        //ejemplo P874-1
        protected static function generar_codigo_aleatorio($letra,$longitud,$numero){
            for($i=1; $i<=$longitud; $i++){
                $aleatorio= rand(0,9);
                $letra.=$aleatorio;
            }
            return $letra."-".$numero;
        }

        /*-------Función  limpiar cadenas--------*/
        protected static function limpiar_cadena($cadena){
            $cadena=trim($cadena);
            $cadena=stripslashes($cadena);//Limpiar los /
            $cadena=str_ireplace("<scrit>", "" ,$cadena); //busca script y lo elimina
            $cadena=str_ireplace("</scrit>", "" ,$cadena); //busca script y lo elimina
            $cadena=str_ireplace("<scrit src", "" ,$cadena); //busca src y lo elimina
            $cadena=str_ireplace("<scrit type=", "" ,$cadena); //busca type y lo elimina
            $cadena=str_ireplace("SELECT * FROM ", "" ,$cadena); //busca select * from y lo elimina
            $cadena=str_ireplace("DELETE FROM ", "" ,$cadena); //busca DELETE FROM y lo elimina
            $cadena=str_ireplace("INSERT INTO", "" ,$cadena); //busca INSERT INTO y lo elimina
            $cadena=str_ireplace("DROP TABLE ", "" ,$cadena); //busca DROP TABLE y lo elimina
            $cadena=str_ireplace("DROP DATABASE", "" ,$cadena); //busca DROP DATABASE elimina
            $cadena=str_ireplace("TRUNCATE TABLE", "" ,$cadena); //busca TRUNCATE TABLE y lo elimina
            $cadena=str_ireplace("SHOW TABLES", "" ,$cadena); //busca SHOW TABLES y lo elimina
            $cadena=str_ireplace("SHOW DATABASES", "" ,$cadena); //busca SHOW DATABASES y lo elimina
            $cadena=str_ireplace("<?php", "" ,$cadena); //busca <?php y lo elimina
            $cadena=str_ireplace("?>", "" ,$cadena); //busca ? > from y lo elimina
            $cadena=str_ireplace("--", "" ,$cadena); //busca  -- y lo elimina
            $cadena=str_ireplace(">", "" ,$cadena); //busca  > y lo elimina
            $cadena=str_ireplace("<", "" ,$cadena); //busca  < y lo elimina
            $cadena=str_ireplace("[", "" ,$cadena); //busca  [ y lo elimina
            $cadena=str_ireplace("]", "" ,$cadena); //busca  ] y lo elimina
            $cadena=str_ireplace("^", "" ,$cadena); //busca  ^ y lo elimina
            $cadena=str_ireplace("==", "" ,$cadena); //busca  == y lo elimina
            $cadena=str_ireplace(";", "" ,$cadena); //busca  ; y lo elimina
            $cadena=str_ireplace("::", "" ,$cadena); //busca  :: y lo elimina
            $cadena=stripslashes($cadena);
            $cadena=trim($cadena);
            return $cadena;
        }

        
        /*-------Función  verificar datos--------*/
        protected static function verificar_datos($filtro,$cadena){
            if(preg_match("/^".$filtro."$/", $cadena)){
                return false; //No tiene errores
            }else{
                return true;
            }
        }

        /*-------Función  verificar fechas--------*/
        protected static function verificar_fecha($fecha){
            $valores=explode('-', $fecha);
            if(count($valores)== 3 && checkdate($valores[1],$valores[2],$valores[0])){
                return false;//No tiene errores
            }else{
                return true; //Hay errores en la fecha
            }
        }

        /*-------Función paginador de tablas--------*/
        protected static function paginador_tablas($pagina_actual,$n_paginas,$url,$botones){

                    //Etiqueas de apertura
            $tabla='<nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center">';

                    if($pagina_actual==1){
                        //Icono disabled <<
                        $tabla.='<li class="page-item disabled"><a class="page-link" ><i class="fas fa-angle-double-left"></i></a></li>';
                    }else{
                        //Icono activo << a la página nº1
                        $tabla.='<li class="page-item "><a class="page-link" href="'.$url.'1/"><i class="fas fa-angle-double-left"></i></a></li>';
                        //Aparece botón anterior
                        '<li class="page-item "><a class="page-link" href="'.$url.($pagina_actual-1).'/">Anterior</a></li>';
                    }


                    //contador iteraciones  ----Botones-----
                    $ci=0;
                    for($i=$pagina_actual; $i<=$n_paginas; $i++){

                        //Establecer el número de botones que queramos
                        if($ci>=$botones){
                            break;
                        }

                        if($pagina_actual==$i){
                                //Para colocar el icono sombreado en la página actual                       //Número de página actual
                            $tabla.='<li class="page-item "><a class="page-link active" href="'.$url.$i.'/">'.$i.'</a></li>';
                        }else{
                                //Quitamos la clase active
                            $tabla.='<li class="page-item "><a class="page-link " href="'.$url.$i.'/">'.$i.'</a></li>';
                        }

                        $ci++;
                    }

                        //Estamos en la última página
                    if($pagina_actual==$n_paginas){
                        //Icono >> disabled
                        $tabla.='<li class="page-item disabled"><a class="page-link" ><i class="fas fa-angle-double-right"></i></a></li>';
                    }else{
                                        //Vamos a la siguiente página
                        $tabla.='<li class="page-item "><a class="page-link" href="'.$url.($pagina_actual+1).'/">Siguiente</a></li>';
                                        //Vamos a la última página
                                '<li class="page-item "><a class="page-link" href="'.$url.$n_paginas.'/"><i class="fas fa-angle-double-right"></i></a></li>';
                                
                    }

                    //Etiquetas cierre
            $tabla.='</ul></nav>';
            return $tabla;        
        }
    }

?>