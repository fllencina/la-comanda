<?php

class ItemsPedido
{
    public $idproducto;
    public $idpedido;
    public $cantidad;
    public $tiempoestimado;
    public $idestado;
    

    public function crearItemPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO ItemsPedido (idproducto, idpedido,cantidad,idestado) VALUES (:idproducto, :idpedido,:cantidad,:idestado)");
        
        $consulta->bindValue(':idproducto', $this->idproducto, PDO::PARAM_INT);
        $consulta->bindValue(':idpedido', $this->idpedido, PDO::PARAM_INT);
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
        $consulta->bindValue(':idestado', $this->idestado, PDO::PARAM_INT);

        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM ItemsPedido");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'ItemsPedido');
    }

    public static function obtenerItemPedido($idproducto,$idPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM ItemsPedido WHERE idproducto = :idproducto and idpedido=:idpedido");
        $consulta->bindValue(':idproducto', $idproducto, PDO::PARAM_INT);
        $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);

        $consulta->execute();

        return $consulta->fetchObject('ItemsPedido');
    }

    public  function modificarItemPedido()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE ItemsPedido SET idestado = :idestado   WHERE idproducto = :idproducto and idpedido=:idpedido");
        $consulta->bindValue(':idestado', $this->idestado, PDO::PARAM_INT);
        $consulta->bindValue(':idproducto', $this->idproducto, PDO::PARAM_INT);
        $consulta->bindValue(':idPedido',  $this->idpedido, PDO::PARAM_INT);
    
      
        $consulta->execute();
    }

   
}