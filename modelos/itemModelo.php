<?php
    require_once 'mainModelo.php';

class itemModelo extends mainModelo{

    /**Modelo agregar item */
    protected static function agregar_item_modelo($datos){
        $sql = mainModelo::conectar()->prepare("INSERT INTO item(item_codigo,item_nombre,item_stock,item_estado,item_detalle) VALUES(:codigo,:nombre,:stock,:estado,:detalle)");
       
        $sql->bindParam(":codigo",$datos['codigo']);
        $sql->bindParam(":nombre",$datos['nombre']);
        $sql->bindParam(":stock",$datos['stock']);
        $sql->bindParam(":estado",$datos['estado']);
        $sql->bindParam(":detalle",$datos['detalle']);

        $sql->execute();

      
        return $sql;
    }

    /**Modelo eliminar item */
    protected static function eliminar_item_modelo($id){
        $sql = mainModelo::conectar()->prepare("DELETE FROM item WHERE item_id=:id");
        $sql->bindParam(":id",$id);
        $sql->execute();

        return $sql;

    }

    
    /*-------Modelo datos item--------*/
    protected static function datos_item_modelo($tipo,$id){

        if($tipo=="Unico"){
            $sql=mainModelo::conectar()->prepare("SELECT * FROM item WHERE item_id=:id");
            $sql->bindParam(":id",$id);
        }else if($tipo=="Conteo"){
            $sql=mainModelo::conectar()->prepare("SELECT item_id FROM item");
        }
        $sql->execute();
        return $sql;

    }

     /*-------Modelo actualizar item--------*/
     protected static function actualizar_item_modelo($datos){
        $sql=mainModelo::conectar()->prepare("UPDATE item SET item_codigo=:codigo,item_nombre=:nombre,item_stock=:stock,item_estado=:estado,item_detalle=:detalle WHERE item_id=:id");
        $sql->bindParam(":codigo",$datos['codigo']);
        $sql->bindParam(":nombre",$datos['nombre']);
        $sql->bindParam(":stock",$datos['stock']);
        $sql->bindParam(":estado",$datos['estado']);
        $sql->bindParam(":detalle",$datos['detalle']);
        $sql->bindParam(":id",$datos['id']);

        $sql->execute();
        return $sql;
       }


}