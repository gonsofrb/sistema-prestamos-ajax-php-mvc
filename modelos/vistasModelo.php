<?php

        
    class vistasModelo{

        /**Modelo para obtener vistas */
        protected static function obtener_vistas_modelo($vistas){
            $listaBlanca = ["home","client-list","client-new","client-search","client-update","item-list","company","item-new","item-search","item-update","reservation-list","reservation-new","reservation-pending","reservation-reservation","reservation-search","reservation-update","user-list","user-new","user-search","user-update"];
            //Comprobación si hay un valor en un array
            if(in_array($vistas, $listaBlanca)){
                //Si encuentra el archivo se devuelve
                if(is_file("vistas/contenidos/".$vistas."-view.php")){
                    $contenido = "vistas/contenidos/".$vistas."-view.php";
                }else{
                    $contenido = "404";
                }
            }elseif($vistas == "login" || $vistas == "index"){
                $contenido ="login";
            }else{
                $contenido ="404";
            }
            return $contenido;
        }
        
    }
?>