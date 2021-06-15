<?php
            
    require_once 'mainModelo.php';

    class loginModelo extends mainModelo{

        /**----Modelo iniciar sesion */
        protected static function iniciar_sesion_modelo($datos){
            $sql=mainModelo::conectar()->prepare("SELECT * FROM usuario WHERE usuario_usuario = :usuario AND  usuario_estado ='Activa' LIMIT 1" );

            $sql->bindParam(":usuario",$datos['usuario']);
            // $sql->bindParam(":clave",$datos['clave']);
            $sql->execute();
            return $sql;

            
        }


    }