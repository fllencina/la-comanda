<?php

class Pedido
{
    public $id;
    public $idmesa;
    public $idusuario;
    public $tiempoestimado;
    public $fechamodificado;
    public $fechaalta;
    public $idestado;
    


    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO Pedido (id,idmesa, idusuario,tiempoestimado,fechamodificado,fechaalta,idestado) VALUES (:id,:idmesa, :idusuario,:tiempoestimado,:fechamodificado,:fechaalta,:idestado)");
        $consulta->bindValue(':id', $this->id, PDO::PARAM_STR);  
        $consulta->bindValue(':idmesa', $this->idmesa, PDO::PARAM_INT);
        $consulta->bindValue(':idusuario', $this->idusuario, PDO::PARAM_INT);
        $consulta->bindValue(':tiempoestimado', $this->tiempoestimado, PDO::PARAM_STR);
        $consulta->bindValue(':fechamodificado', $this->fechamodificado, PDO::PARAM_INT); 
        $consulta->bindValue(':fechaalta', $this->fechaalta, PDO::PARAM_STR);
        $consulta->bindValue(':idestado', $this->idestado, PDO::PARAM_INT);

        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM Pedido");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }
    public static function obtenerTodosSector($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $sql= "SELECT prod.descripcion,ip.cantidad,ped.idmesa,ped.fechaalta,s.nombre as sector,u.usuario ,ep.descripcion as idestado FROM `productos` as prod
        left join itemspedido as ip on ip.idproducto=prod.id
        left JOIN pedido as ped on ped.id=ip.idPedido
        left join sectores as s on s.id=prod.idsector
        left join usuario as u on u.id=ped.idusuario
        left join estadopedido as ep on ep.id=ip.idestado
        where prod.idsector=:idsector";

        $consulta = $objAccesoDatos->prepararConsulta($sql);
        $consulta->bindParam(':idsector', $id);
       // $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM Pedido where idsector=:idsector");
       // $consulta->bindValue(':idsector', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }
    
    public static function obtenerPedido($idmesa)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM Pedido WHERE idmesa = :idmesa");
        $consulta->bindValue(':idmesa', $idmesa, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }

    public  function modificarPedido()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE Pedido SET idmesa = :idmesa, idusuario = :idusuario ,tiempoestimado=:tiempoestimado , fechamodificado= :fechamodificado, fechaalta= :fechaalta, idestado= :idestado  WHERE id = :id");
        $consulta->bindValue(':id', $this->id, PDO::PARAM_STR);  
        $consulta->bindValue(':idmesa', $this->idmesa, PDO::PARAM_INT);
        $consulta->bindValue(':idusuario', $this->idusuario, PDO::PARAM_INT);
        $consulta->bindValue(':tiempoestimado', $this->tiempoestimado, PDO::PARAM_STR);
        $consulta->bindValue(':fechamodificado', $this->fechamodificado, PDO::PARAM_INT); 
        $consulta->bindValue(':fechaalta', $this->fechaalta, PDO::PARAM_STR);
        $consulta->bindValue(':idestado', $this->idestado, PDO::PARAM_INT);

        $consulta->execute();
    }

    
}