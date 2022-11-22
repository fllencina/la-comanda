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