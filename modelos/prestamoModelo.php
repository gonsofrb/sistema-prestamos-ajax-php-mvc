<?php
    require_once 'mainModelo.php';

class prestamoModelo extends mainModelo{

     /*-------Modelo agregar prestamo--------*/
     protected static function agregar_prestamo_modelo($datos){
        $sql=mainModelo::conectar()->prepare("INSERT INTO prestamo(prestamo_codigo,prestamo_fecha_inicio,prestamo_hora_inicio,prestamo_fecha_final,prestamo_hora_final,prestamo_cantidad,prestamo_total,prestamo_pagado,prestamo_estado,prestamo_observacion,usuario_id,cliente_id) VALUES(:codigo,:fecha_inicio,:hora_inicio,:fecha_final,:hora_final,:cantidad,:total,:pagado,:estado,:observacion,:usuario_id,:cliente_id)");

        $sql->bindParam(":codigo",$datos['codigo']);
        $sql->bindParam(":fecha_inicio",$datos['fecha_inicio']);
        $sql->bindParam(":hora_inicio",$datos['hora_inicio']);
        $sql->bindParam(":fecha_final",$datos['fecha_final']);
        $sql->bindParam(":hora_final",$datos['hora_final']);
        $sql->bindParam(":cantidad",$datos['cantidad']);
        $sql->bindParam(":total",$datos['total']);
        $sql->bindParam(":pagado",$datos['pagado']);
        $sql->bindParam(":estado",$datos['estado']);
        $sql->bindParam(":observacion",$datos['observacion']);
        $sql->bindParam(":usuario_id",$datos['usuario_id']);
        $sql->bindParam(":cliente_id",$datos['cliente_id']);
        $sql->execute();

        return $sql;
     }/**------Fin agregar prestamo */


     /*-------Modelo agregar detalle--------*/
     protected static function agregar_detalle_modelo($datos){
        $sql=mainModelo::conectar()->prepare("INSERT INTO detalle(detalle_cantidad,detalle_formato,detalle_tiempo,detalle_costo_tiempo,detalle_descripcion,prestamo_codigo,item_id) VALUES(:cantidad,:formato,:tiempo,:costo,:descripcion,:prestamo,:item)");

        $sql->bindParam(":cantidad",$datos['cantidad']);
        $sql->bindParam(":formato",$datos['formato']);
        $sql->bindParam(":tiempo",$datos['tiempo']);
        $sql->bindParam(":costo",$datos['costo']);
        $sql->bindParam(":descripcion",$datos['descripcion']);
        $sql->bindParam(":prestamo",$datos['prestamo']);
        $sql->bindParam(":item",$datos['item']);
       
        $sql->execute();

        return $sql;
     }/**------Fin agregar detalle */


      /*-------Modelo agregar pago--------*/
      protected static function agregar_pago_modelo($datos){
        $sql=mainModelo::conectar()->prepare("INSERT INTO pago(pago_total,pago_fecha,prestamo_codigo) VALUES(:total,:fecha,:codigo)");

        $sql->bindParam(":total",$datos['total']);
        $sql->bindParam(":fecha",$datos['fecha']);
        $sql->bindParam(":codigo",$datos['codigo']);
       
        $sql->execute();

        return $sql;
     }/**------Fin agregar pago */


      /*-------Modelo eliminar prestamo--------*/
      protected static function eliminar_prestamo_modelo($codigo,$tipo){
         if($tipo=="Prestamo"){
            $sql=mainModelo::conectar()->prepare("DELETE FROM prestamo WHERE prestamo_codigo=:codigo");
            
         }elseif($tipo=="Detalle"){
            $sql=mainModelo::conectar()->prepare("DELETE FROM detalle WHERE prestamo_codigo=:codigo");
         }elseif($tipo=="Pago"){
            $sql=mainModelo::conectar()->prepare("DELETE FROM pago WHERE prestamo_codigo=:codigo");
         }
         $sql->bindParam(":codigo",$codigo);
       
         $sql->execute();
 
         return $sql;

      }


      /*-------Modelo datos prestamo--------*/
      protected static function datos_prestamo_modelo($tipo,$id){
         if($tipo=="Unico"){
            $sql=mainModelo::conectar()->prepare("SELECT * FROM prestamo WHERE prestamo_id=:id");
            $sql->bindParam(":id",$id);
             
         }elseif($tipo=="Conteo_Reservacion"){
            $sql=mainModelo::conectar()->prepare("SELECT prestamo_id FROM prestamo WHERE prestamo_estado='Reservacion'");
            
         }elseif($tipo=="Conteo_Prestamos"){
            $sql=mainModelo::conectar()->prepare("SELECT prestamo_id FROM prestamo WHERE prestamo_estado='Prestamo'");
         }elseif($tipo=="Conteo_Finalizado"){
            $sql=mainModelo::conectar()->prepare("SELECT prestamo_id FROM prestamo WHERE prestamo_estado='Finalizado'");
         }elseif($tipo=="Conteo"){
            $sql=mainModelo::conectar()->prepare("SELECT prestamo_id FROM prestamo");
         }elseif($tipo=="Detalle"){
            $sql=mainModelo::conectar()->prepare("SELECT * FROM detalle WHERE prestamo_codigo=:codigo");
            $sql->bindParam(":codigo",$id);
         }elseif($tipo=="Pago"){
            $sql=mainModelo::conectar()->prepare("SELECT * FROM pago WHERE prestamo_codigo=:codigo");
            $sql->bindParam(":codigo",$id);
         }
         $sql->execute();
 
         return $sql;
      }


      
      /*-------Modelo actualizar prestamo--------*/
      protected static function actualizar_prestamo_modelo($datos){
         if($datos['Tipo']=="Pago"){
            $sql=mainModelo::conectar()->prepare("UPDATE prestamo SET prestamo_pagado=:monto WHERE prestamo_codigo=:codigo");
            $sql->bindParam(":monto",$datos['monto']);
         }elseif($datos['Tipo']=="Prestamo"){
            $sql=mainModelo::conectar()->prepare("UPDATE prestamo SET prestamo_estado=:estado,prestamo_observacion=:observacion WHERE prestamo_codigo=:codigo");
            $sql->bindParam(":estado",$datos['estado']);
            $sql->bindParam(":observacion",$datos['observacion']);
         }
         $sql->bindParam(":codigo",$datos['codigo']);

         $sql->execute();
 
         return $sql;

      }

     
}